<?php

use yii\db\Migration;

/**
 * Class m260220_122514_add_credit_columns
 */
class m260220_122514_add_credit_columns extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('credit', 'rejected', $this->integer()->defaultValue(0));
        $this->addColumn('credit', 'rejected_user_id', $this->integer());
        $this->addColumn('credit', 'rejected_time', $this->integer());
        $this->addColumn('credit', 'rejected_reason', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('credit', 'rejected');
        $this->dropColumn('credit', 'rejected_user_id');
        $this->dropColumn('credit', 'rejected_time');
        $this->dropColumn('credit', 'rejected_reason');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260220_122514_add_credit_columns cannot be reverted.\n";

        return false;
    }
    */
}
