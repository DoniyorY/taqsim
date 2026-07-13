<?php

use yii\db\Migration;

/**
 * Class m251009_121538_create_table_algenix_logs
 */
class m251009_121538_create_table_algenix_logs extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('algenix_logs', [
            'id' => $this->primaryKey(),
            'created' => $this->integer()->notNull(),
            'amount' => $this->integer()->notNull(),
            'content' => $this->string(),
            'ip' => $this->string(),
            'action'=>$this->string(),
            'req'=>$this->text(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('algenix_logs');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m251009_121538_create_table_algenix_logs cannot be reverted.\n";

        return false;
    }
    */
}
