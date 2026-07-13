<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "payment_basket".
 *
 * @property int $id
 * @property int|null $payment_id
 * @property int $payment_created
 * @property int $payment_type
 * @property int $method_id
 * @property int $pay_type
 * @property int $company_id
 * @property string $content
 * @property int|null $credit_plan_id
 * @property int $user_id
 * @property int $credit_id
 * @property int $credit_type_id
 * @property int $amount
 * @property int|null $deleted_user_id
 * @property int|null $deleted_time
 */
class PaymentBasket extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment_basket';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payment_id', 'payment_created', 'payment_type', 'method_id', 'pay_type', 'company_id', 'credit_plan_id', 'user_id', 'credit_id', 'credit_type_id', 'amount', 'deleted_user_id', 'deleted_time'], 'integer'],
            [['payment_created', 'payment_type', 'pay_type', 'company_id', 'content', 'user_id', 'credit_id', 'credit_type_id', 'amount'], 'required'],
            [['content'], 'string'],
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
            'payment_created' => Yii::$app->params['payment_date'][$lang],
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
            'date_end' => Yii::$app->params['labels_doc_date_end'][$lang],
            'deleted_user_id' => 'Deleted User ID',
            'deleted_time' => 'Deleted Time',
        ];
    }

    public static function getTotalCount($dataProvider, $fieldName)
    {
        $totalBalance = 0;

        foreach ($dataProvider as $item) {
            $totalBalance += $item[$fieldName];
        }

        return yii::$app->formatter->asDecimal($totalBalance, 0);
    }

    public function getDeletedUser()
    {
        return $this->hasOne(User::class, ['id' => 'deleted_user_id']);
    }

    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }

    public function getPlan()
    {
        return $this->hasOne(CreditPlan::className(), ['id' => 'credit_plan_id']);
    }

    public function getCredit()
    {
        return $this->hasOne(Credit::classname(), ['id' => 'credit_id']);
    }

    public function getType()
    {
        return $this->hasOne(CreditType::className(), ['id' => 'credit_type_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
