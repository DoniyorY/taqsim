<?php

namespace common\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "credit_plan".
 *
 * @property int $id
 * @property int $credit_id
 * @property int $company_id
 * @property int $client_id
 * @property int $created
 * @property int $pay_summa
 * @property int $pay_status
 * @property int $summa_real
 * @property int $summa_bonus
 * @property int $is_sent_sms
 * @property int $yurist_goday
 * @property string $content
 */
class CreditPlan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $credit_type_id;

    public static function tableName()
    {
        return 'credit_plan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['credit_id', 'company_id', 'client_id', 'created', 'pay_summa', 'pay_status', 'summa_real', 'summa_bonus', 'is_sent_sms', 'yurist_goday'], 'required'],
            [['credit_id', 'company_id', 'client_id', 'created', 'pay_summa', 'pay_status', 'summa_real', 'summa_bonus', 'is_sent_sms', 'yurist_goday', 'credit_type_id'], 'integer'],
            [['content'], 'string']
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
            'credit_id' => Yii::$app->params['labels_credit_id'][$lang],
            'company_id' => Yii::$app->params['labels_company'][$lang],
            'client_id' => Yii::$app->params['labels_client'][$lang],
            'created' => Yii::$app->params['amount_month'][$lang],
            'pay_summa' => Yii::$app->params['amount'][$lang],
            'pay_status' => Yii::$app->params['labels_status'][$lang],
            'summa_real' => 'Summa Real',
            'summa_bonus' => 'Summa Bonus',
            'is_sent_sms' => 'Cмс',
            'content' => Yii::$app->params['labels_content'][$lang],
            'yurist_goday' => Yii::$app->params['labels_yurist_goday'][$lang],
            'credit_type_id' => Yii::$app->params['labels_credit_type'][$lang]
        ];
    }

    public function getCredit()
    {
        return $this->hasOne(Credit::className(), ['id' => 'credit_id']);
    }

    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }

    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }

    public function getClientphone()
    {
        return $this->hasOne(\common\models\Client::className(), ['id' => 'client_id']);
    }


    public static function getTotalPayment($pay_summa, $id)
    {

        $payment = (new Query)->select(['credit_plan_id', 'SUM(amount) as amount'])
            ->from('payments')
            ->where(['credit_plan_id' => $id])
            ->one();
        $p = 0;
        if ($payment) $p = $payment['amount'];
        return intval($pay_summa) - intval($p);
    }
}
