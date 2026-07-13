<?php
//$credit_model=\common\models\old\Credit::find()->all();
//$credit_model = \common\models\old\Credit::find()->where(['between', 'doc_numb', 9001, 12000])->all();
?>
<div style="width: 100%;height:50px;overflow: hidden;display: block;"></div>

<table style="width: 100%;border-collapse: collapse;" border="1">
    <?php foreach ($credit_model as $credit): ?>
        <?php
        $q1 = \Yii::$app->security->generateRandomString(2);
        $q2 = \Yii::$app->security->generateRandomString(2);
        $q3 = \Yii::$app->security->generateRandomString(2);
        $q4 = \Yii::$app->security->generateRandomString(2);
        $q5 = \Yii::$app->security->generateRandomString(2);
        ?>
        <?php
        $new_credit_model = new \common\models\Credit();
        $new_credit_model->id = $credit->doc_numb;

        $new_credit_model->credit_status = $credit->status;
        $new_credit_model->token = $q1 . '-' . $q2 . '-' . $q3 . '-' . $q4 . '-' . $q5;
        $new_credit_model->user_id = $credit->user_id;
        $new_credit_model->credit_type_id = 6;

        $new_credit_model->doc_date_start = $credit->doc_date;
        $new_credit_model->doc_date_end = $credit->doc_date_end;
        $new_credit_model->pay_day = $credit->doc_month_day;
        $new_credit_model->company_id = $credit->company_id;
        $new_credit_model->region_id = $credit->region_id;
        $new_credit_model->content = $credit->content;

        $new_credit_model->guar_name = $credit->zalog;
        $new_credit_model->guar_type = $credit->zalog_turi;
        $new_credit_model->guar_count = $credit->zalog_miqdori;
        $new_credit_model->guar_summa = $credit->zalog_summa;

        $new_credit_model->witness_seller_fullname = $credit->sotuv_name;
        $new_credit_model->witness_seller_address = $credit->sotuv_adress;
        $new_credit_model->witness_seller_phone = '-';;
        $new_credit_model->witness_seller_passport = $credit->sotuv_pass;

        $new_credit_model->witness_customer_fullname = $credit->xarid_name;
        $new_credit_model->witness_customer_address = $credit->xarid_adress;
        $new_credit_model->witness_customer_phone = '-';
        $new_credit_model->witness_customer_passport = $credit->xarid_pass;

        $new_credit_model->self_price = $credit->summa_real;
        $new_credit_model->percent = $credit->percent;
        $new_credit_model->prepaid_summa = $credit->avans;
        $new_credit_model->method_id = $credit->avans_type;
        $new_credit_model->month_count = $credit->month;

        $new_credit_model->doc_total_price = $credit->summa;
        $new_credit_model->doc_total_text = $credit->summa_text;
        $new_credit_model->created = $credit->created;

        //client
        //поиск старого клиента
        $find_old_client = \common\models\old\Client::findOne($credit->client_id);

        if (isset($find_old_client)) {
            //проверка на новой базе данных на существование
            $find_in_news = \common\models\Client::find()->where(['passport_numb' => $find_old_client->pass_numb])->one();
            if ($find_in_news == true) {
                //клиент существует
                $new_credit_model->client_id = $find_in_news->id;
            } else {
                //новый клиент перезаписывает
                $new_new_client = new \common\models\Client();
                $new_new_client->created = $find_old_client->created;
                $new_new_client->fullname = $find_old_client->name;
                $new_new_client->phone = $find_old_client->phone;
                $new_new_client->birthday = $find_old_client->birthday;
                $new_new_client->passport_numb = $find_old_client->pass_numb;
                $new_new_client->passport_whose = $find_old_client->pass_who;
                $new_new_client->passport_enddate = $find_old_client->pass_date;
                $new_new_client->address = $find_old_client->adress;
                $new_new_client->image = '';
                $new_new_client->client_type = 0;
                $new_new_client->guarantor_id = 0;
                $new_new_client->credit_limit = 0;
                if ($new_new_client->save()) {
                    //ищем со старой базы телефон номера
                    $phones = \common\models\old\Phones::find()->where(['client_id' => $find_old_client->id])->all();
                    foreach ($phones as $one_phone) {
                        $phon_model = new \common\models\ClientPhones();
                        $phon_model->created = time();
                        $phon_model->client_id = $new_new_client->id;
                        $phon_model->content = $one_phone->name;
                        $phon_model->numb = $one_phone->phone;
                        $phon_model->save(false);
                    }
                    $new_credit_model->client_id = $new_new_client->id;
                }
            }

        }

        //у старого клиента НЕ сущесвует кафил
        if ($find_old_client->kafil_id == null) {
            //старый кафил как клиент
            $new_new_client = new \common\models\Client();
            $new_new_client->created = $find_old_client->created;
            $new_new_client->fullname = $find_old_client->kafil_name;
            $new_new_client->phone = $find_old_client->kafil_phone;
            $new_new_client->birthday = $find_old_client->kafil_birthday;
            $new_new_client->passport_numb = $find_old_client->kafil_pass_numb;
            $new_new_client->passport_whose = $find_old_client->kafil_pass_who;
            $new_new_client->passport_enddate = $find_old_client->kafil_pass_date;
            $new_new_client->address = $find_old_client->kafil_adress;
            $new_new_client->image = '';
            $new_new_client->client_type = 1;
            $new_new_client->credit_limit = 0;
            if ($new_new_client->save()) {
                $new_credit_model->guarantor_id = $new_new_client->id;
            }
        }
        //у старого клиента сущесвует кафил
        if ($find_old_client->kafil_id != null) {
            //проверка кафиля на новой клиентской базе
            $find_in_kafil_old_model = \common\models\old\Kafil::findOne($find_old_client->kafil_id);
            if ($find_in_kafil_old_model == true) {
                //кафил сущесвтует на новой клиент базе
                $kafil_in_new_klient = \common\models\Client::find()->where(['passport_numb' => $find_old_client->kafil_pass_numb])->one();
                if ($kafil_in_new_klient == true) {
                    $new_credit_model->guarantor_id = $kafil_in_new_klient->id;
                } else {
                    //кафил не существует на новой базе
                    $new_new_client = new \common\models\Client();
                    $new_new_client->created = $find_old_client->created;
                    $new_new_client->fullname = $find_old_client->kafil_name;
                    $new_new_client->phone = $find_old_client->kafil_phone;
                    $new_new_client->birthday = $find_old_client->kafil_birthday;
                    $new_new_client->passport_numb = $find_old_client->kafil_pass_numb;
                    $new_new_client->passport_whose = $find_old_client->kafil_pass_who;
                    $new_new_client->passport_enddate = $find_old_client->kafil_pass_date;
                    $new_new_client->address = $find_old_client->kafil_adress;
                    $new_new_client->image = '';
                    $new_new_client->client_type = 1;
                    $new_new_client->credit_limit = 0;
                    if ($new_new_client->save()) {
                        $new_credit_model->guarantor_id = $kafil_in_new_klient->id;
                    }
                }
            }
        }


        $new_credit_model->save(false);


        ?>
    <?php endforeach; ?>
    <?php echo 'All done step1'; ?>
</table>
