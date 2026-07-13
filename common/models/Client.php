<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "client".
 *
 * @property int $id
 * @property int $created
 * @property string $fullname
 * @property int $phone
 * @property string $birthday
 * @property string $passport_numb
 * @property string $passport_pinfl
 * @property string $passport_whose
 * @property string $passport_enddate
 * @property string $address
 * @property string $image
 * @property string $client_type
 * @property string $guarantor_id
 * @property string $credit_limit
 * @property int $is_blacklist
 * @property int $blacklist_user_id
 * @property int $blacklist_time
 * @property int $credit_score
 */
class Client extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'client';
    }

    /**
     * {@inheritdoc}
     */
    public $imageFile;

    public function rules()
    {
        return [
            [['created', 'fullname', 'phone', 'birthday', 'passport_numb', 'passport_whose', 'passport_enddate', 'address', 'phone', 'client_type', 'passport_pinfl'], 'required'],
            [['created', 'client_type', 'guarantor_id', 'credit_limit'], 'integer'],
            [['fullname', 'birthday', 'passport_numb', 'passport_whose', 'passport_enddate', 'address', 'phone'], 'string', 'max' => 255],
            [['imageFile'], 'file',
                'skipOnEmpty' => true,
                'extensions' => 'png, jpg, jpeg, pdf',
                'maxSize' => 5 * 1024 * 1024,
                'tooBig' => 'Файл не должен превышать 5MB.'
            ],
            [['passport_pinfl'], 'string', 'max' => 14, 'min'=>14],
            [['passport_pinfl'], 'unique', 'targetClass' => '\common\models\Client', 'message' => 'Tizimda siz tergan PINFL mavjud'],
          //  ['fullname', 'match', 'pattern' => '/^\S+\s+\S+\s+\S+$/', 'message' => 'Введите ФИО полностью.'],

        ];
    }

    public function summaGuar($id)
    {

        $guar_id = $id;
        $credits = \common\models\Credit::find()->where(['guarantor_id' => $guar_id]);
        $credits_list = $credits->all();
        $credits_summa = $credits->sum('doc_total_price');
        $credits_summa_pre = $credits->sum('prepaid_summa');
        $ids = \yii\helpers\ArrayHelper::getColumn($credits_list, 'id');
        $paymetns = \common\models\Payments::find()->where(['credit_id' => $ids])->sum('amount');
        return $credits_summa - $credits_summa_pre - $paymetns;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        $lang = Yii::$app->language;
        return [
            'id' => 'ID',
            'created' => Yii::$app->params['labels_created'][$lang],
            'fullname' => Yii::$app->params['labels_client_fullname'][$lang],
            'phone' => Yii::$app->params['labels_phone'][$lang],
            'birthday' => Yii::$app->params['labels_birthday'][$lang],
            'passport_numb' => Yii::$app->params['labels_passport'][$lang],
            'passport_whose' => Yii::$app->params['labels_passport_whose'][$lang],
            'passport_enddate' => Yii::$app->params['labels_passport_enddate'][$lang],
            'address' => Yii::$app->params['labels_address'][$lang],
            'image' => Yii::$app->params['labels_image'][$lang],
            'imageFile' => Yii::$app->params['labels_image'][$lang],
            'client_type' => Yii::$app->params['labels_client_type'][$lang],
            'guarantor_id' => Yii::$app->params['labels_guarantor'][$lang],
            'credit_limit' => Yii::$app->params['labels_limit'][$lang],
            'passport_pinfl'=>'ПИНФЛ',
            'credit_score'=>'Кредитный счёт'
        ];
    }

    public function getInfo()
    {
        return $this->fullname . ' | ' . Yii::$app->formatter->asDate($this->birthday, "php:d.m.Y") . ' | ' . $this->phone . ' | ' . 'Паспорт № ' . $this->passport_numb;
    }
}
