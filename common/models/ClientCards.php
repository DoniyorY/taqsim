<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "client_cards".
 *
 * @property int $id
 * @property int $client_id
 * @property int $card_number
 * @property int $card_name
 * @property string $card_date
 * @property string $client_phone
 * @property int $status
 * @property int $created
 * @property int $token
 * @property int $algenix_card_id
 */
class ClientCards extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'client_cards';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_id', 'card_number', 'card_name', 'card_date', 'status', 'created'], 'required'],
            [['client_id', 'status', 'created','algenix_card_id'], 'integer'],
            [['card_date', 'card_name', 'token','client_phone'], 'string', 'max' => 255],
            [['card_number', 'card_date'], 'unique', 'targetClass' => '\common\models\ClientCards', 'message' => 'This card has already been taken.']
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
            'card_number' => 'Номер карты',
            'card_name' => 'Наименование карты',
            'card_date' => 'Срок карты',
            'status' => 'Статус',
            'created' => 'Дата создания',
        ];
    }
}
