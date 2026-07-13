<?php
    /*$invoice_old=\common\models\old\Faktura::find()->all();
    foreach($invoice_old as $old_one){

        $new_inc=new \common\models\CreditInvoice();
        $new_inc->id=$old_one->id;
        $new_inc->created=$old_one->created;
        $new_inc->credit_id=$old_one->credit_id;
        $new_inc->status=$old_one->status;
        $new_inc->user_id=$old_one->user_id;
        $new_inc->save();

        $new_sign=new \common\models\CreditSign();
        $new_sign->credit_id=$old_one->credit_id;
        $new_sign->guarantor_sign='-';
        $new_sign->client_sign='-';
        $new_sign->save();

    }*/


   // $item_old=\common\models\old\Item::find()->all();
    //$item_old=\common\models\old\Item::find()->where(['between','id',1,20000])->all();
    /*foreach($item_old as $one){

        $new_item=new \common\models\CreditItem();
        $new_item->id=$one->id;
        $new_item->credit_id=$one->credit_id;
        $new_item->title=$one->name;
        $new_item->count=$one->count;
        $new_item->summa=$one->summa;
        $new_item->save(false);
    }*/



?>
<?php echo 'All done step2';  ?>
