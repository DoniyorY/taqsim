<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "credit_plan_contents".
 *
 * @property int $id
 * @property int $created
 * @property string $content
 * @property int $credit_plan_id
 * @property int $credit_id
 */
class CreditPlanContents extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'credit_plan_contents';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created', 'content', 'credit_plan_id', 'credit_id'], 'required'],
            [['created', 'credit_plan_id', 'credit_id'], 'integer'],
            [['content'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created' => 'Дата создания',
            'content' => 'Примечание',
            'credit_plan_id' => 'План ID',
            'credit_id' => 'Кредит ID',
        ];
    }

    public function getCredit()
    {
        return $this->hasOne(Credit::className(), ['id' => 'credit_id']);
    }

    public function getPlan()
    {
        return $this->hasOne(CreditPlan::className(), ['id' => 'credit_plan_id']);
    }
}
