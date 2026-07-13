<?php
//$old_pays=\common\models\old\Payment::find()->all();
$old_pays = \common\models\old\Payment::find()->where(['between', 'id', 74070, 74093])->all();
?>
<?php
foreach($old_pays as $old_pay){
    ////***********
    $paymodel= new \common\models\Payments();
    $paymodel->id=$old_pay->id;
    $paymodel->created=$old_pay->created;
    if(!isset($old_pay->cre->id)) {
        $paymodel->company_id=0;
    } else {
        $paymodel->company_id=$old_pay->cre->company_id;
    }
    $paymodel->amount=$old_pay->summa;
    $paymodel->content=($old_pay->comment==null)?'-':$old_pay->comment;
    $paymodel->credit_plan_id=$old_pay->plan_id;
    $paymodel->user_id=$old_pay->user_id;
    $paymodel->credit_id=$old_pay->credit_id;
    $paymodel->credit_type_id=6;
    $paymodel->pay_type=4;
    if($old_pay->summa<0){
        //Расход
        $paymodel->payment_type=1;
    }
    else {
        //Приход
        $paymodel->payment_type=0;
    }
    if($old_pay->summa==0){
        $paymodel->payment_type=0;
    }
    //cash
    if($old_pay->type==1){ $paymodel->method_id=0; }
    //card
    if($old_pay->type==2){ $paymodel->method_id=1; }
    //old type=0 to nothing
    if($old_pay->type==0){ $paymodel->method_id=-1; }

    try {
        $paymodel->save(false);
    } catch (\yii\base\ErrorException $e) {
        echo  $old_pay->id.'<br/>';
    }

}
?>
<?php echo 'All done step4-3';  ?>
<h1>Hello world!!!</h1>
