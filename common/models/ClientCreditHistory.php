<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "client_credit_history".
 *
 * @property int $id
 * @property int $client_id
 * @property int $score
 * @property float|null $avg_delay Средняя просрочка в днях
 * @property string|null $reason
 * @property int $created_at
 *
 * @property Client $client
 */
class ClientCreditHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'client_credit_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_id', 'score', 'created_at'], 'required'],
            [['client_id', 'score', 'created_at'], 'integer'],
            [['avg_delay'], 'number'],
            [['reason'], 'string', 'max' => 255],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::class, 'targetAttribute' => ['client_id' => 'id']],
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
            'score' => 'Score',
            'avg_delay' => 'Avg Delay',
            'reason' => 'Reason',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Client]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::class, ['id' => 'client_id']);
    }
}
