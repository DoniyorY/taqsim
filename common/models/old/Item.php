<?php

namespace common\models\old;

use Yii;

/**
 * This is the model class for table "item".
 *
 * @property int $id
 * @property int $credit_id
 * @property int $client_id
 * @property string $name
 * @property int $summa
 * @property int $count
 */
class Item extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item';
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
            [['credit_id', 'client_id', 'name', 'summa', 'count'], 'required'],
            [['credit_id', 'client_id', 'summa', 'count'], 'integer'],
            [['name'], 'string', 'max' => 256],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'credit_id' => 'Credit ID',
            'client_id' => 'Client ID',
            'name' => 'Name',
            'summa' => 'Summa',
            'count' => 'Count',
        ];
    }
}
