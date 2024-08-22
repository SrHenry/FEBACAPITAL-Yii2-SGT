<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tasks}}`.
 */
class m240820_050323_create_tasks_table extends Migration
{
    const MAIN_TABLE_NAME = '{{%task}}';
    const SUB_TABLE_NAME = '{{%task_statuses}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $statuses = [
            'pending' => [1, 'pendente'],
            'in_progress' => [2, 'em progresso'],
            'finished' => [3, 'concluída'],
        ];

        $this->createTable(self::SUB_TABLE_NAME, [
            'id' => $this->primaryKey()->notNull(),
            'name' => $this->string()->notNull(),
        ]);

        $this->batchInsert(self::SUB_TABLE_NAME, ['id', 'name'], [
            $statuses['pending'],
            $statuses['in_progress'],
            $statuses['finished'],
        ]);

        $this->createTable(self::MAIN_TABLE_NAME, [
            'id' => $this->primaryKey()->notNull(),
            'creator_id' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'description' => $this->text()->notNull(),
            'due_date' => $this->dateTime()->notNull(),
            'status_id' => $this->integer()->notNull()->defaultValue($statuses['pending'][0]),

            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('FK_creator_user_id', self::MAIN_TABLE_NAME, 'creator_id', '{{%user}}', 'id');
        $this->addForeignKey('FK_status_id', self::MAIN_TABLE_NAME, 'status_id', self::SUB_TABLE_NAME, 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::MAIN_TABLE_NAME);
        $this->dropTable(self::SUB_TABLE_NAME);
    }
}
