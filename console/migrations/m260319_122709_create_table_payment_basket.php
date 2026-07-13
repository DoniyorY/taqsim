<?php

use yii\db\Migration;

/**
 * Class m260319_122709_create_table_payment_basket
 */
class m260319_122709_create_table_payment_basket extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%payment_basket}}', [
            'id' => $this->primaryKey(),
            'payment_id'=>$this->integer(),
            'payment_created' => $this->integer()->notNull(),
            'payment_type' => $this->integer()->notNull(),
            'method_id' => $this->integer()->notNull()->defaultValue(0),
            'pay_type' => $this->integer()->notNull(),
            'company_id' => $this->integer()->notNull(),
            'content' => $this->text()->notNull(),
            'credit_plan_id' => $this->integer(),
            'user_id' => $this->integer()->notNull(),
            'credit_id' => $this->integer()->notNull(),
            'credit_type_id' => $this->integer()->notNull(),
            'amount' => $this->integer()->notNull(),
            'deleted_user_id'=>$this->integer(),
            'deleted_time'=>$this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('payment_basket');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260319_122709_create_table_payment_basket cannot be reverted.\n";

        return false;
    }
    */
}
