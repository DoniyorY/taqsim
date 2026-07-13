<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "algenix_logs".
 *
 * @property int $id
 * @property int $created
 * @property int $amount
 * @property string|null $content
 * @property string|null $ip
 * @property string|null $action
 * @property string|null $req
 */
class AlgenixLogs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'algenix_logs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created', 'amount'], 'required'],
            [['created', 'amount'], 'integer'],
            [['req'], 'string'],
            [['content', 'ip', 'action'], 'string', 'max' => 255],
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
            'amount' => 'Amount',
            'content' => 'Content',
            'ip' => 'Ip',
            'action' => 'Action',
            'req' => 'Req',
        ];
    }
}
