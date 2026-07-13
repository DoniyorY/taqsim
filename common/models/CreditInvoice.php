<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "credit_invoice".
 *
 * @property int $id
 * @property int $created
 * @property int $credit_id
 * @property int $status
 * @property int $user_id
 */
class CreditInvoice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'credit_invoice';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created', 'credit_id', 'status', 'user_id'], 'required'],
            [['created', 'credit_id', 'status', 'user_id'], 'integer'],
        ];
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
            'credit_id' => Yii::$app->params['labels_credit_id'][$lang],
            'status' => Yii::$app->params['labels_status'][$lang],
            'user_id' => Yii::$app->params['labels_user'][$lang],
        ];
    }
    public function getCredit(){
        return $this->hasOne(Credit::className(),['id' => 'credit_id']);
    }
    public function getUser(){
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
