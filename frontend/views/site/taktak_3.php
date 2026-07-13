<?php //$old_plan=\common\models\old\Plan::find()->all();
    $old_plan=\common\models\old\Plan::find()->where(['between','id',100001,120000])->all();
?>


<?php foreach($old_plan as $one_item){

    $new_plan=new \common\models\CreditPlan();
    $new_plan->id=$one_item->id;
        $find_credit_client=\common\models\Credit::findOne($one_item->credit_id);
        if(isset($find_credit_client)){
            $new_plan->client_id=$find_credit_client->client_id; //
        } else {
            $new_plan->client_id=-111;
        }

    $new_plan->credit_id=$one_item->credit_id;
    $new_plan->company_id=$one_item->company_id;

    $new_plan->created=$one_item->created;
    $new_plan->pay_summa=$one_item->summa;
        if($one_item->status==1){
            $new_plan->pay_status=2;
        }
        if($one_item->status==0){
            $new_plan->pay_status=0;
        }

    $new_plan->summa_real=$one_item->sum_real;
    $new_plan->summa_bonus=$one_item->sum_bonus;
    $new_plan->is_sent_sms=1;
    $new_plan->yurist_goday=0;
        $find_note=\common\models\old\PlanNotes::findOne($one_item->id);
        if(isset($find_note)){
            $text=date('j.m.Y',$find_note->created).'|'.$find_note->content;
            $new_plan->content=$text;
        } else {
            $new_plan->content='-';
        }
    $new_plan->save(false);

} ?>
<?php echo 'All done step3';  ?>