<?php

use yii\db\mysql\Schema;

class m130821_090353_update_status_format_to_integer_in_books_table extends \yii\db\Migration
{
    public function up() {
        $this->db->createCommand()
            ->update('books', array('status' => 1), 'status="available"')
            ->execute();

        $this->db->createCommand()
            ->update('books', array('status' => 2), 'status="taken"')
            ->execute();

        $this->db->createCommand()
            ->alterColumn('books', 'status', Schema::TYPE_INTEGER . ' not null default 1')
            ->execute();

    }

    public function down() {
        $this->db->createCommand()
            ->alterColumn('books', 'status', Schema::TYPE_STRING . ' not null default "available"')
            ->execute();

        $this->db->createCommand()
            ->update('books', array('status' => 'available'), 'status=1')
            ->execute();

        $this->db->createCommand()
            ->update('books', array('status' => 'taken'), 'status=2')
            ->execute();
    }
}
