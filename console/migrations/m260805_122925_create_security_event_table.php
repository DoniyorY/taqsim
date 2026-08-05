<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%security_event}}`.
 */
class m260805_122925_create_security_event_table extends Migration
{
    /**
     * {@inheritdoc}
     */
   public function safeUp()
   {
      $this->createTable('{{%security_event}}', [
         'id' => $this->bigPrimaryKey(),
         
         'created_at' => $this->integer()->notNull(),
         'request_id' => $this->string(36),
         
         'event_type' => $this->string(50)->notNull(),
         'rule' => $this->string(100),
         'severity' => $this->tinyInteger()->notNull()->defaultValue(1),
         
         // logged, blocked, banned
         'action' => $this->string(20)->notNull()->defaultValue('logged'),
         
         'ip' => $this->string(45),
         'method' => $this->string(10),
         'url' => $this->text(),
         
         'user_id' => $this->integer(),
         'user_agent' => $this->text(),
         
         'matched_value' => $this->text(),
         'payload' => $this->text(),
         'payload_hash' => $this->char(64),
      ]);
      
      $this->createIndex(
         'idx-security_event-created_at',
         '{{%security_event}}',
         'created_at'
      );
      
      $this->createIndex(
         'idx-security_event-ip',
         '{{%security_event}}',
         'ip'
      );
      
      $this->createIndex(
         'idx-security_event-type',
         '{{%security_event}}',
         'event_type'
      );
      
      $this->createIndex(
         'idx-security_event-action',
         '{{%security_event}}',
         'action'
      );
   }
   
   public function safeDown()
   {
      $this->dropTable('{{%security_event}}');
   }
}
