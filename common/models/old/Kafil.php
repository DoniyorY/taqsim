<?php

namespace common\models\old;

use Yii;

/**
 * This is the model class for table "kafil".
 *
 * @property int $id
 * @property int $created
 * @property string $name
 * @property string $birthday
 * @property string $phone
 * @property string $adress
 * @property string $pass_numb
 * @property string $pass_date
 * @property string $pass_who
 * @property int $status
 */
class Kafil extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kafil';
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
            [['created', 'name', 'birthday', 'phone', 'adress', 'pass_numb', 'pass_date', 'pass_who', 'status'], 'required'],
            [['created', 'status'], 'integer'],
            [['name', 'birthday', 'phone', 'adress', 'pass_numb', 'pass_date', 'pass_who'], 'string', 'max' => 256],
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
            'name' => 'Name',
            'birthday' => 'Birthday',
            'phone' => 'Phone',
            'adress' => 'Adress',
            'pass_numb' => 'Pass Numb',
            'pass_date' => 'Pass Date',
            'pass_who' => 'Pass Who',
            'status' => 'Status',
        ];
    }
}
