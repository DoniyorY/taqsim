<?php

namespace common\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "credit".
 *
 * @property int $id
 * @property int $credit_status
 * @property string $token
 * @property int $user_id
 * @property int $credit_type_id
 * @property int $guarantor_id
 * @property string $doc_date_start
 * @property string $doc_date_end
 * @property string $pay_day
 * @property int $company_id
 * @property int $region_id
 * @property string $content
 * @property string $guar_name
 * @property string $guar_type
 * @property int $guar_count
 * @property int $guar_summa
 * @property string|null $witness_seller_fullname
 * @property string|null $witness_seller_address
 * @property int|null $witness_seller_phone
 * @property string|null $witness_seller_passport
 * @property string|null $witness_customer_fullname
 * @property string|null $witness_customer_address
 * @property int|null $witness_customer_phone
 * @property string|null $witness_customer_passport
 * @property int $self_price
 * @property int $percent
 * @property int $prepaid_summa
 * @property int $method_id
 * @property int $month_count
 * @property int $doc_total_price
 * @property string $doc_total_text
 * @property string $client_id
 * @property string $created
 * @property int $rejected
 * @property int $rejected_user_id
 * @property int $rejected_time
 * @property int $rejected_reason
 */
class Credit extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'credit';
    }

    public $client_birthday;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['credit_status', 'token', 'user_id', 'credit_type_id', 'doc_date_start', 'doc_date_end', 'pay_day', 'company_id', 'region_id'], 'required'],
            [['credit_status', 'client_id', 'user_id', 'credit_type_id', 'guarantor_id', 'company_id', 'region_id', 'guar_count', 'guar_summa', 'witness_seller_phone', 'witness_customer_phone', 'self_price', 'percent', 'prepaid_summa', 'method_id', 'month_count', 'doc_total_price', 'created'], 'integer'],
            [['content'], 'string'],
            [['token', 'doc_date_start', 'doc_date_end', 'pay_day', 'guar_name', 'guar_type', 'witness_seller_fullname', 'witness_seller_address', 'witness_seller_passport', 'witness_customer_fullname', 'witness_customer_address', 'witness_customer_passport', 'doc_total_text'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        $lang = Yii::$app->language;
        return [
            'id' => Yii::$app->params['labels_credit_id'][$lang],
            'credit_status' => Yii::$app->params['labels_status'][$lang],
            'client_id' => Yii::$app->params['labels_client'][$lang],
            'token' => Yii::$app->params['labels_token'][$lang],
            'user_id' => Yii::$app->params['labels_user'][$lang],
            'credit_type_id' => Yii::$app->params['labels_credit_type'][$lang],
            'guarantor_id' => Yii::$app->params['labels_guarantor'][$lang],
            'doc_date_start' => Yii::$app->params['labels_doc_date_start'][$lang],
            'doc_date_end' => Yii::$app->params['labels_doc_date_end'][$lang],
            'pay_day' => Yii::$app->params['labels_pay_day'][$lang],
            'company_id' => Yii::$app->params['labels_company'][$lang],
            'region_id' => Yii::$app->params['labels_region'][$lang],
            'content' => Yii::$app->params['labels_content'][$lang],
            'guar_name' => Yii::$app->params['labels_guar_name'][$lang],
            'guar_type' => Yii::$app->params['labels_guar_type'][$lang],
            'guar_count' => Yii::$app->params['labels_guar_count'][$lang],
            'guar_summa' => Yii::$app->params['labels_guar_summa'][$lang],
            'witness_seller_fullname' => Yii::$app->params['labels_fullname'][$lang],
            'witness_seller_address' => Yii::$app->params['labels_address'][$lang],
            'witness_seller_phone' => Yii::$app->params['labels_phone'][$lang],
            'witness_seller_passport' => Yii::$app->params['labels_passport'][$lang],
            'witness_customer_fullname' => Yii::$app->params['labels_fullname'][$lang],
            'witness_customer_address' => Yii::$app->params['labels_address'][$lang],
            'witness_customer_phone' => Yii::$app->params['labels_phone'][$lang],
            'witness_customer_passport' => Yii::$app->params['labels_passport'][$lang],
            'self_price' => Yii::$app->params['labels_self_price'][$lang],
            'percent' => Yii::$app->params['labels_percent'][$lang],
            'prepaid_summa' => Yii::$app->params['labels_prepaid_summa'][$lang],
            'method_id' => Yii::$app->params['labels_method'][$lang],
            'month_count' => Yii::$app->params['labels_month_count'][$lang],
            'doc_total_price' => Yii::$app->params['labels_doc_total_price'][$lang],
            'doc_total_text' => Yii::$app->params['labels_doc_total_text'][$lang],
            'rejected_user_id' => Yii::$app->params['rejected_user'][$lang],
            'rejected_time' => Yii::$app->params['rejected_time'][$lang],
            'rejected_reason' => Yii::$app->params['rejected_reason'][$lang],
        ];
    }

    public function getRejectedUser()
    {
        return $this->hasOne(User::class, ['id' => 'rejected_user_id']);
    }

    public function getInfo($id)
    {
        $credit_id = $id;
        $payments = \common\models\Payments::find()->where(['credit_id' => $credit_id, 'payment_type' => 0])->sum('amount');
        return $payments;
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getCreditType()
    {
        return $this->hasOne(\common\models\CreditType::className(), ['id' => 'credit_type_id']);
    }

    public function getRegion()
    {
        return $this->hasOne(\common\models\Region::className(), ['id' => 'region_id']);
    }

    public function getCompany()
    {
        return $this->hasOne(\common\models\Company::className(), ['id' => 'company_id']);
    }

    public function getClient()
    {
        return $this->hasOne(\common\models\Client::className(), ['id' => 'client_id']);
    }

    public function getClientphone()
    {
        return $this->hasOne(\common\models\Client::className(), ['id' => 'client_id']);
    }

    public function getGuarantor()
    {
        return $this->hasOne(\common\models\Client::className(), ['id' => 'guarantor_id']);
    }

    public function getLabel()
    {
        return Yii::$app->params['labels_credit_id'][Yii::$app->language] . $this->id . ' От ' . Yii::$app->formatter->asDate($this->doc_date_start, "php:d.m.Y");
    }

    public function getPlans()
    {
        return $this->hasMany(CreditPlan::class, ['credit_id' => 'id']);
    }
}
