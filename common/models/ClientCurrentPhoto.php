<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "client_current_photo".
 *
 * @property int $id
 * @property int $client_id
 * @property int $credit_id
 * @property string $image
 * @property int $user_id
 * @property int $created
 */
class ClientCurrentPhoto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'client_current_photo';
    }
    public $imageFile;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_id', 'credit_id', 'image', 'user_id', 'created','imageFile'], 'required'],
            [['client_id', 'credit_id', 'user_id', 'created'], 'integer'],
            [['imageFile'], 'string', 'max' => 255],
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
            'credit_id' => 'Credit ID',
            'image' => 'Image',
            'user_id' => 'User ID',
            'created' => 'Created',
            'imageFile'=>'Фотография'
        ];
    }
}
