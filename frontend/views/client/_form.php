<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Client */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="client-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">

        <div class="col-md-2">
            <?Php if (Yii::$app->controller->action->id == 'create'): ?>
                <?= $form->field($model, 'imageFile')->fileInput(['required' => 'required']) ?>
            <?Php else: ?>
                <?= $form->field($model, 'imageFile')->fileInput() ?>
            <?php endif; ?>
            <img src="https://cdn1.vectorstock.com/i/1000x1000/81/60/passport-sample-data-personal-page-female-vector-28278160.jpg"
                 class="img-fluid"
            >
        </div>
        <div class="col-md-10">
            <div class="row">

                <div class="col-md-3">
                    <h4 class="border-bottom"><?php echo Yii::$app->params['client_passport_sub_title'][Yii::$app->language]; ?></h4>
                </div>
                <div id="p1" class="col-md-3">

                </div>
                <div class="col-md-3">
                    <a id="buttona" href="" class="btn btn-primary" style="visibility: hidden;"></a>
                </div>
                <div class="col-md-3"></div>

                <div class="col-md-3">
                    <?= $form->field($model, 'passport_numb')
                        ->widget(\yii\widgets\MaskedInput::class, [
                            'mask' => 'AA9999999',
                        ])->textInput(['onchange' => 'checkClient(this.value)']) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'passport_pinfl')->textInput(['maxlength'=>true]) ?>
                </div>
                <div class="col-md-3"> <?= $form->field($model, 'passport_whose')->textInput(['maxlength' => true]) ?> </div>
                <div class="col-md-3">   <?= $form->field($model, 'passport_enddate')->widget(\yii\widgets\MaskedInput::class, [
                        'mask' => '99.99.9999',
                    ])->textInput(['placeholder' => 'дд.мм.гггг']) ?> </div>
                <div class="col-md-12"><br/></div>
                <div class="col-md-12">
                    <h4 class="border-bottom"><?php echo Yii::$app->params['client_about_sub_title'][Yii::$app->language]; ?></h4>
                </div>
                <div class="col-md-8"> <?= $form->field($model, 'fullname')->textInput(['maxlength' => true]) ?></div>
                <div class="col-md-2"> <?= $form->field($model, 'client_type')->dropDownList(Yii::$app->params['client_type'][Yii::$app->language]) ?></div>
                <div class="col-md-2"> <?= $form->field($model, 'credit_limit')->textInput() ?> </div>
                <div class="col-md-3"> <?= $form->field($model, 'phone')
                        ->textInput(['type' => 'number', 'value' => ($model->isNewRecord) ? '998' : $model->phone]) ?>
                </div>
                <div class="col-md-2"> <?= $form->field($model, 'birthday')->textInput(['type' => 'date']) ?> </div>
                <div class="col-md-7"> <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?> </div>


                <div class="form-group col-md-12"><br/>
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-block btn-success']) ?>
                </div>
            </div>
        </div>


    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>

    // Example POST method implementation:
    async function getData(url = '', data = {}) {
        // Default options are marked with *
        const response = await fetch(url, {
            method: 'GET', // *GET, POST, PUT, DELETE, etc.
        });
        return response.json(); // parses JSON response into native JavaScript objects
    }

    function displayButton(client_id, client_fullname) {
        if (client_id) {
            let button_link = document.getElementById('buttona');
            button_link.textContent = client_fullname;
            button_link.setAttribute('href', 'https://taqsimsavdo.uz/ru/client/view?id=' + client_id);
            button_link.setAttribute('style', 'visibility:visibility;')
            document.getElementById("p1").innerHTML = ` <div class="alert alert-success" role="alert">
                Клиент успешно найден в системе!
            </div>`;


        }
    }

    function checkClient(text) {
        //console.log(text);
        getData(`https://taqsimsavdo.uz/site/check?n=${text}`)
            .then((data) => {
                //client_id=data.id; // JSON data parsed by `data.json()` call
                displayButton(data.id, data.fullname);
            });
    };
</script>