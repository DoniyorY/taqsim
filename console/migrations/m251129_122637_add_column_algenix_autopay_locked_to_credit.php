<?php

use yii\db\Migration;

/**
 * Class m251129_122637_add_column_algenix_autopay_locked_to_credit
 */
class m251129_122637_add_column_algenix_autopay_locked_to_credit extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('credit','algenix_autopay_locked',$this->tinyInteger()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('credit','algenix_autopay_locked');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m251129_122637_add_column_algenix_autopay_locked_to_credit cannot be reverted.\n";

        return false;
    }
    */
}
