<?php

namespace common\models\old;

use Yii;

/**
 * This is the model class for table "plan_notes".
 *
 * @property int $id
 * @property int $plan_id
 * @property int $created
 * @property string $content
 * @property int $user_id
 * @property int $company_id
 */
class PlanNotes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plan_notes';
    }
    // add the function below:
    public static function getDb() {
        return Yii::$app->get('db2'); // second database
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['plan_id', 'created', 'content', 'user_id', 'company_id'], 'required'],
            [['plan_id', 'created', 'user_id', 'company_id'], 'integer'],
            [['content'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'plan_id' => 'Plan ID',
            'created' => 'Created',
            'content' => 'Content',
            'user_id' => 'User ID',
            'company_id' => 'Company ID',
        ];
    }
}
