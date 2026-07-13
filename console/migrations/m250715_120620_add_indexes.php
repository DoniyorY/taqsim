<?php

use yii\db\Migration;

/**
 * Class m250715_120620_add_indexes
 */
class m250715_120620_add_indexes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // ===== credit =====
        $this->createIndex('idx_credit_status_created', 'credit', ['credit_status', 'created']);
        $this->createIndex('idx_credit_client_id', 'credit', 'client_id');
        $this->createIndex('idx_credit_company_id', 'credit', 'company_id');
        $this->createIndex('idx_credit_user_id', 'credit', 'user_id');

        // ===== credit_plan =====
        $this->createIndex('idx_credit_plan_credit_created', 'credit_plan', ['credit_id', 'created']);
        $this->createIndex('idx_credit_plan_company_created', 'credit_plan', ['company_id', 'created']);
        $this->createIndex('idx_credit_plan_id', 'credit_plan', 'id');

        // ===== payments =====
        $this->createIndex('idx_payments_credit_plan_id', 'payments', 'credit_plan_id');
        $this->createIndex('idx_payments_credit_id', 'payments', 'credit_id');
    }

    public function safeDown()
    {
        // ===== credit =====
        $this->dropIndex('idx_credit_status_created', 'credit');
        $this->dropIndex('idx_credit_client_id', 'credit');
        $this->dropIndex('idx_credit_company_id', 'credit');
        $this->dropIndex('idx_credit_user_id', 'credit');

        // ===== credit_plan =====
        $this->dropIndex('idx_credit_plan_credit_created', 'credit_plan');
        $this->dropIndex('idx_credit_plan_company_created', 'credit_plan');
        $this->dropIndex('idx_credit_plan_id', 'credit_plan');

        // ===== payments =====
        $this->dropIndex('idx_payments_credit_plan_id', 'payments');
        $this->dropIndex('idx_payments_credit_id', 'payments');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250715_120620_add_indexes cannot be reverted.\n";

        return false;
    }
    */
}
