<?php

class m130721_104519_alter_unique_index_for_user_email extends \yii\db\Migration
{
    public function up() {
        $this->db->createCommand()
            ->createIndex('idx_email', 'users', 'email', true)
            ->execute();
    }

    public function down() {
        $this->db->createCommand()
            ->dropIndex('idx_email', 'users')
            ->execute();
        return true;
    }
}
