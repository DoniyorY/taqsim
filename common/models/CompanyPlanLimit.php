<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "company_plan_limit".
 *
 * @property int $id
 * @property int $company_id
 * @property int $type
 * @property int $limit
 * @property int $created
 * @property int $status
 * @property int $user_id
 */
class CompanyPlanLimit extends \yii\db\ActiveRecord
{
    const TYPE_CONTRACTS = 1;
    const TYPE_PAYMENTS = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'company_plan_limit';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'limit'], 'required'],
            [['company_id', 'type', 'limit', 'created', 'status', 'user_id'], 'integer'],
            ['type', 'in', 'range' => array_keys(self::typeLabels())],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'Компания',
            'type' => 'Тип',
            'limit' => 'Лимит',
            'created' => 'Дата создания',
            'status' => 'Статус',
            'user_id' => 'Пользователь',
        ];
    }

    public static function typeLabels()
    {
        return [
           'ru'=>[
              self::TYPE_CONTRACTS => 'План оформленных договоров',
              self::TYPE_PAYMENTS => 'План по сбору денег с договоров',
           ],
           'uz'=>[
              self::TYPE_CONTRACTS => 'Расмийлаштирилган шартномалар плани',
              self::TYPE_PAYMENTS => 'Ундирувчилар плани',
           ]
           
        ];
    }

    public function getTypeLabel()
    {
        return self::typeLabels()[$this->type] ?? $this->type;
    }

    public function getStatusLabel()
    {
        return $this->status ? 'Активный' : 'Неактивный';
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
