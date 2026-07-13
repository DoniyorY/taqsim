<?php

use yii\db\Migration;

/**
 * Class m251209_094720_add_column_to_algenix_logs
 */
class m251209_094720_add_column_to_algenix_logs extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('algenix_logs', 'transaction_id', $this->string(255));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('algenix_logs', 'transaction_id');
        return false;

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m251209_094720_add_column_to_algenix_logs cannot be reverted.\n";

        return false;
    }
    */
}
