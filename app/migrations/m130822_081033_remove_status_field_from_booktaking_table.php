<?php

use yii\db\mysql\Schema;

class m130822_081033_remove_status_field_from_booktaking_table extends \yii\db\Migration
{
    public function up()
    {
        $this->db->createCommand()
            ->dropColumn('book_taking', 'status')
            ->execute();
        return true;
    }

    public function down()
    {
        $this->db->createCommand()
            ->addColumn('book_taking', 'status', Schema::TYPE_SMALLINT . ' not null default 1')
            ->execute();
        return true;
    }
}
