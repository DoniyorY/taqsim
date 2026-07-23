<?php

use yii\db\Migration;

class m260715_101709_create_table_user_salary extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->createTable('user_salary', [
         'id'=>$this->primaryKey(),
         'user_id'=>$this->integer()->notNull(),
         'salary'=>$this->bigInteger()->notNull(),
         'created'=>$this->integer()->notNull(),
      ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m260715_101709_create_table_user_salary cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260715_101709_create_table_user_salary cannot be reverted.\n";

        return false;
    }
    */
}
