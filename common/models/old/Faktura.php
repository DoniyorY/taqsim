<?php

namespace common\models\old;

use Yii;

/**
 * This is the model class for table "faktura".
 *
 * @property int $id
 * @property int $created
 * @property int $credit_id
 * @property int $status
 * @property int $user_id
 */
class Faktura extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'faktura';
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
            [['created', 'credit_id', 'status', 'user_id'], 'required'],
            [['created', 'credit_id', 'status', 'user_id'], 'integer'],
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
            'status' => 'Status',
            'user_id' => 'User ID',
        ];
    }
}
