<?php

use yii\db\mysql\Schema;

class m130730_075222_add_status_field_in_booktaking_table extends \yii\db\Migration
{
	public function up()
	{
        $this->db->createCommand()
            ->addColumn('book_taking', 'status', Schema::TYPE_SMALLINT . ' not null default 1')
            ->execute();
        return true;
	}

	public function down()
	{
        $this->db->createCommand()
            ->dropColumn('book_taking', 'status')
            ->execute();
        return true;
	}
}
