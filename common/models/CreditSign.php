<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "credit_sign".
 *
 * @property int $id
 * @property int $credit_id
 * @property string $guarantor_sign
 * @property string $client_sign
 */
class CreditSign extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'credit_sign';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['credit_id', 'guarantor_sign', 'client_sign'], 'required'],
            [['credit_id'], 'integer'],
            [['guarantor_sign', 'client_sign'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'credit_id' => 'Credit ID',
            'guarantor_sign' => 'Guarantor Sign',
            'client_sign' => 'Client Sign',
        ];
    }
    public function getCredit(){
        return $this->hasOne(Credit::className(),['id' => 'credit_id']);
    }
}
