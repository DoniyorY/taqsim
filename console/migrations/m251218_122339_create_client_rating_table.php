<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%client_rating}}`.
 */
class m251218_122339_create_client_rating_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%client}}', 'credit_score', $this->tinyInteger()
            ->notNull()
            ->defaultValue(0)
            ->comment('Кредитный рейтинг: 0-нет данных, 1-плохо, 2-хорошо, 3-очень хорошо')
        );

        $this->createIndex(
            'idx-client-credit_score',
            '{{%client}}',
            'credit_score'
        );

        $this->createTable('{{%client_credit_history}}', [
            'id' => $this->primaryKey(),
            'client_id' => $this->integer()->notNull(),
            'score' => $this->tinyInteger()->notNull(),
            'avg_delay' => $this->decimal(5,2)->null()->comment('Средняя просрочка в днях'),
            'reason' => $this->string(255)->null(),
            'created_at' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-history-client',
            '{{%client_credit_history}}',
            'client_id',
            '{{%client}}',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-history-client-created',
            '{{%client_credit_history}}',
            ['client_id', 'created_at']
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey('fk-history-client', '{{%client_credit_history}}');
        $this->dropTable('{{%client_credit_history}}');

        $this->dropIndex('idx-client-credit_score', '{{%client}}');
        $this->dropColumn('{{%client}}', 'credit_score');



    }
}
