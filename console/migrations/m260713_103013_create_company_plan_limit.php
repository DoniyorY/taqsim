<?php

use yii\db\Migration;

class m260713_103013_create_company_plan_limit extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->createTable('company_plan_limit', [
         'id' => $this->primaryKey(),
         'company_id' => $this->integer()->notNull(),
         'type' => $this->integer()->notNull(),
         'limit' => $this->bigInteger()->notNull(),
         'created' => $this->integer()->notNull(),
         'status'=>$this->integer()->notNull(),
         'user_id'=>$this->integer()->notNull(),
      ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->dropTable('company_plan_limit');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260713_103013_create_company_plan_limit cannot be reverted.\n";

        return false;
    }
    */
}
