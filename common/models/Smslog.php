<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "smslog".
 *
 * @property int $id
 * @property string $recipient
 * @property int|null $message_id
 * @property string|null $originator
 * @property string|null $text
 * @property int|null $status
 */
class Smslog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smslog';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['recipient'], 'required'],
            [['message_id', 'status'], 'integer'],
            [['recipient'], 'string', 'max' => 15],
            [['originator'], 'string', 'max' => 255],
            [['text'], 'string', 'max' => 160],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'recipient' => 'Recipient',
            'message_id' => 'Message ID',
            'originator' => 'Originator',
            'text' => 'Text',
            'status' => 'Status',
        ];
    }
}
