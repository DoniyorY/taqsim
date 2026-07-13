<?php

namespace common\models\old;

use Yii;

/**
 * This is the model class for table "phones".
 *
 * @property int $id
 * @property int $client_id
 * @property string $phone
 * @property string $name
 */
class Phones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'phones';
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
            [['client_id', 'phone', 'name'], 'required'],
            [['client_id'], 'integer'],
            [['phone'], 'string', 'max' => 12],
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
            'client_id' => 'Client ID',
            'phone' => 'Phone',
            'name' => 'Name',
        ];
    }
}
