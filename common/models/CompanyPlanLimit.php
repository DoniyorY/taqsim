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
            [['company_id', 'type', 'limit', 'created', 'status', 'user_id'], 'required'],
            [['company_id', 'type', 'limit', 'created', 'status', 'user_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'Company ID',
            'type' => 'Type',
            'limit' => 'Limit',
            'created' => 'Created',
            'status' => 'Status',
            'user_id' => 'User ID',
        ];
    }

}
