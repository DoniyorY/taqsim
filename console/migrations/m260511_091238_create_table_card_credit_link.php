<?php

use yii\db\Migration;

/**
 * Class m260511_091238_create_table_card_credit_link
 */
class m260511_091238_create_table_card_credit_link extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('card_credit_link', [
            'id' => $this->primaryKey(),
            'credit_id' => $this->integer(),
            'card_id' => $this->integer(),
            'created' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('card_credit_link');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260511_091238_create_table_card_credit_link cannot be reverted.\n";

        return false;
    }
    */
}
