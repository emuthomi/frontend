<?php

use yii\db\Migration;

class m170802_140638_t_submission extends Migration {
    public function up() {
        $this->createTable('t_submission', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'problem_id' => $this->integer()->notNull(),
            'language' => $this->integer()->notNull(),
            'code' => $this->text()->notNull(),
            'status' => $this->integer()->notNull(),
            'runtime' => $this->integer()->notNull(),
            'memory' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull()
        ]);

        $this->addForeignKey(
            'fk_t_submission_problem_id',
            't_submission',
            'problem_id',
            't_problem',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_t_submission_user_id',
            't_submission',
            'user_id',
            't_user',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx_problem_id',
            't_submission',
            'problem_id'
        );

        $this->createIndex(
            'idx_user_id',
            't_submission',
            'user_id'
        );
    }

    public function down() {
        $this->dropForeignKey(
            'fk_t_submission_problem_id',
            't_submission'
        );

        $this->dropForeignKey(
            'fk_t_submission_user_id',
            't_submission'
        );

        $this->dropIndex(
            'idx_user_id',
            't_submission'
        );

        $this->dropIndex(
            'idx_problem_id',
            't_submission'
        );

        $this->dropTable('t_submission');
    }
}