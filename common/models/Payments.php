<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "payments".
 *
 * @property int $id
 * @property int $created
 * @property int $payment_type
 * @property int $method_id
 * @property int $pay_type
 * @property int $company_id
 * @property int $content
 * @property int $credit_plan_id
 * @property int $user_id
 * @property int $credit_id
 * @property int $credit_type_id
 * @property int $amount
 */
class Payments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payments';
    }
    public $date_begin;
    public $date_end;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created', 'payment_type', 'method_id', 'pay_type', 'company_id', 'content', 'credit_plan_id', 'user_id', 'credit_id', 'credit_type_id', 'amount'], 'required'],
            [['created', 'payment_type', 'method_id', 'pay_type', 'company_id', 'credit_plan_id', 'user_id', 'credit_id', 'credit_type_id', 'amount'], 'integer'],
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
            'created' => Yii::$app->params['payment_date'][$lang],
            'payment_type' => Yii::$app->params['labels_payment_type'][$lang],
            'method_id' => Yii::$app->params['labels_method'][$lang],
            'pay_type' => Yii::$app->params['labels_pay_type'][$lang],
            'company_id' => Yii::$app->params['labels_company'][$lang],
            'content' => Yii::$app->params['labels_content'][$lang],
            'credit_plan_id' => 'План оплаты',
            'user_id' => Yii::$app->params['labels_user'][$lang],
            'credit_id' => Yii::$app->params['labels_credit_id'][$lang],
            'credit_type_id' => Yii::$app->params['labels_credit_type'][$lang],
            'amount' => Yii::$app->params['amount'][$lang],
            'date_begin' => Yii::$app->params['labels_doc_date_start'][$lang],
            'date_end' => Yii::$app->params['labels_doc_date_end'][$lang]
        ];
    }
    public static function getTotalCount($dataProvider, $fieldName){
        $totalBalance = 0;

        foreach ($dataProvider as $item){
            $totalBalance += $item[$fieldName];
        }

        return yii::$app->formatter->asDecimal($totalBalance,0);
    }
    public function getCompany(){
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }
    public function getPlan(){
        return $this->hasOne(CreditPlan::className(), ['id' => 'credit_plan_id']);
    }
    public function getCredit(){
        return $this->hasOne(Credit::classname(), ['id' => 'credit_id']);
    }
    public function getType(){
        return $this->hasOne(CreditType::className(), ['id' => 'credit_type_id']);
    }
    public function getUser(){
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
