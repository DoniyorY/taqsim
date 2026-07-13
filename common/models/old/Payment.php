<?php

namespace common\models\old;

use Yii;

/**
 * This is the model class for table "payment".
 *
 * @property int $id
 * @property int $created
 * @property int $credit_id
 * @property int $plan_id
 * @property int $summa
 * @property int $type
 * @property string|null $comment
 * @property int $user_id
 */
class Payment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment';
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
            [['created', 'credit_id', 'plan_id', 'summa', 'type', 'user_id'], 'required'],
            [['created', 'credit_id', 'plan_id', 'summa', 'type', 'user_id'], 'integer'],
            [['comment'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created' => 'Created',
            'credit_id' => 'Credit ID',
            'plan_id' => 'Plan ID',
            'summa' => 'Summa',
            'type' => 'Type',
            'comment' => 'Comment',
            'user_id' => 'User ID',
        ];
    }
}
