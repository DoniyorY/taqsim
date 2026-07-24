<?php

use yii\db\Migration;

class m260724_114022_add_column_to_credit_type extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('credit_type', 'type', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->dropColumn('credit_type', 'type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260724_114022_add_column_to_credit_type cannot be reverted.\n";

        return false;
    }
    */
}
