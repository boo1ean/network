<?php

use yii\db\mysql\Schema;

class m130824_142534_change_link_field_on_resource_id_field_in_books_table extends \yii\db\Migration
{
    public function up() {
        $this->db->createCommand()
            ->dropColumn('books', 'link')
            ->execute();

        $this->db->createCommand()
            ->addColumn('books', 'resource_id', Schema::TYPE_INTEGER . ' not null default 0')
            ->execute();
        return true;
    }

    public function down() {
        $this->db->createCommand()
            ->dropColumn('books', 'resource_id')
            ->execute();

        $this->db->createCommand()
            ->addColumn('books', 'link', Schema::TYPE_STRING . ' default NULL')
            ->execute();
        return true;
    }
}
