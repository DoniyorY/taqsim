<?php
    $model=\common\models\old\Kafil::find()->all();

    foreach($model as $item):
            $pass=str_replace(' ', '', $item->pass_numb);;
        $item->pass_numb=$pass;
            $birth=str_replace(' ', '.', $item->birthday);
            $item->birthday=$birth;
        $birth=str_replace(',', '.', $item->birthday);
        $item->birthday=$birth;
        $item->save(false);
        echo $item->id;
    endforeach;


    $model_client=\common\models\old\Client::find()->all();

    foreach($model_client as $item):

        $birth=str_replace(' ', '.', $item->birthday);
        $item->birthday=$birth;
        $birth=str_replace(',', '.', $item->birthday);
        $item->birthday=$birth;


        $birth=str_replace(' ', '.', $item->kafil_birthday);
        $item->kafil_birthday=$birth;
        $birth=str_replace(',', '.', $item->kafil_birthday);
        $item->kafil_birthday=$birth;

        $pass=str_replace(' ', '', $item->pass_numb);;
        $item->pass_numb=$pass;

        $pass_k=str_replace(' ', '', $item->kafil_pass_numb);;
        $item->kafil_pass_numb=$pass_k;



        $item->save(false);
        echo $item->id;
    endforeach;


?>