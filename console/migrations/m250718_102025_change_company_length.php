<?php

use yii\db\Migration;

/**
 * Class m250718_102025_change_company_length
 */
class m250718_102025_change_company_length extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('company','name',$this->string(255));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250718_102025_change_company_length cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250718_102025_change_company_length cannot be reverted.\n";

        return false;
    }
    */
}
