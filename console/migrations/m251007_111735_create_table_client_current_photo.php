<?php

use yii\db\Migration;

/**
 * Class m251007_111735_create_table_client_current_photo
 */
class m251007_111735_create_table_client_current_photo extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('client_current_photo', [
            'id' => $this->primaryKey(),
            'client_id' => $this->integer()->notNull(),
            'credit_id' => $this->integer()->notNull(),
            'image' => $this->string()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('client_current_photo');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m251007_111735_create_table_client_current_photo cannot be reverted.\n";

        return false;
    }
    */
}
