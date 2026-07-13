<?php

namespace common\models\old;

use Yii;

/**
 * This is the model class for table "client".
 *
 * @property int $id
 * @property string $name
 * @property string $birthday
 * @property string $phone
 * @property string $adress
 * @property string $pass_numb
 * @property string $pass_date
 * @property string $pass_who
 * @property int $created
 * @property int $status
 * @property int|null $kafil_id
 * @property string|null $kafil_name
 * @property string|null $kafil_birthday
 * @property string|null $kafil_phone
 * @property string|null $kafil_adress
 * @property string|null $kafil_pass_numb
 * @property string|null $kafil_pass_date
 * @property string|null $kafil_pass_who
 */
class Client extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'client';
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
            [['name', 'birthday', 'phone', 'adress', 'pass_numb', 'pass_date', 'pass_who', 'created', 'status'], 'required'],
            [['created', 'status', 'kafil_id'], 'integer'],
            [['name', 'birthday', 'adress', 'pass_who', 'kafil_name', 'kafil_birthday', 'kafil_phone', 'kafil_adress', 'kafil_pass_numb', 'kafil_pass_date', 'kafil_pass_who'], 'string', 'max' => 256],
            [['phone'], 'string', 'max' => 12],
            [['pass_numb', 'pass_date'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'birthday' => 'Birthday',
            'phone' => 'Phone',
            'adress' => 'Adress',
            'pass_numb' => 'Pass Numb',
            'pass_date' => 'Pass Date',
            'pass_who' => 'Pass Who',
            'created' => 'Created',
            'status' => 'Status',
            'kafil_id' => 'Kafil ID',
            'kafil_name' => 'Kafil Name',
            'kafil_birthday' => 'Kafil Birthday',
            'kafil_phone' => 'Kafil Phone',
            'kafil_adress' => 'Kafil Adress',
            'kafil_pass_numb' => 'Kafil Pass Numb',
            'kafil_pass_date' => 'Kafil Pass Date',
            'kafil_pass_who' => 'Kafil Pass Who',
        ];
    }
}
