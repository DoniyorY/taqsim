<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "security_event".
 *
 * @property int $id
 * @property int $created_at
 * @property string|null $request_id
 * @property string $event_type
 * @property string|null $rule
 * @property int $severity
 * @property string $action
 * @property string|null $ip
 * @property string|null $method
 * @property string|null $url
 * @property int|null $user_id
 * @property string|null $user_agent
 * @property string|null $matched_value
 * @property string|null $payload
 * @property string|null $payload_hash
 */
class SecurityEvent extends \yii\db\ActiveRecord
{
   
   
   /**
    * {@inheritdoc}
    */
   public static function tableName()
   {
      return 'security_event';
   }
   
   /**
    * {@inheritdoc}
    */
   public function rules()
   {
      return [
         [['request_id', 'rule', 'ip', 'method', 'url', 'user_id', 'user_agent', 'matched_value', 'payload', 'payload_hash'], 'default', 'value' => null],
         [['severity'], 'default', 'value' => 1],
         [['action'], 'default', 'value' => 'logged'],
         [['created_at', 'event_type'], 'required'],
         [['created_at', 'severity', 'user_id'], 'integer'],
         [['url', 'user_agent', 'matched_value', 'payload'], 'string'],
         [['request_id'], 'string', 'max' => 36],
         [['event_type'], 'string', 'max' => 50],
         [['rule'], 'string', 'max' => 100],
         [['action'], 'string', 'max' => 20],
         [['ip'], 'string', 'max' => 45],
         [['method'], 'string', 'max' => 10],
         [['payload_hash'], 'string', 'max' => 64],
      ];
   }
   
   /**
    * {@inheritdoc}
    */
   public function attributeLabels()
   {
      return [
         'id' => 'ID',
         'created_at' => 'Created At',
         'request_id' => 'Request ID',
         'event_type' => 'Event Type',
         'rule' => 'Rule',
         'severity' => 'Severity',
         'action' => 'Action',
         'ip' => 'Ip',
         'method' => 'Method',
         'url' => 'Url',
         'user_id' => 'User ID',
         'user_agent' => 'User Agent',
         'matched_value' => 'Matched Value',
         'payload' => 'Payload',
         'payload_hash' => 'Payload Hash',
      ];
   }
   
}
