<?php

use yii\db\mysql\Schema;

class m130823_140139_add_status_user_book_to_the_booktaking_table extends \yii\db\Migration
{
    public function up() {
        $this->db->createCommand()
            ->addColumn('book_taking', 'status_user_book', Schema::TYPE_SMALLINT . ' not null default 1')
            ->execute();
        return true;
    }

    public function down() {
        $this->db->createCommand()
            ->dropColumn('book_taking', 'status_user_book')
            ->execute();
        return true;
    }
}
