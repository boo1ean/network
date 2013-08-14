<?php

use yii\db\Schema;

class m130814_083222_add_link_field_to_books extends \yii\db\Migration
{
	public function up()
	{
        $this->db->createCommand()
            ->addColumn('books', 'link', Schema::TYPE_STRING)
            ->execute();
        return true;
	}

	public function down()
	{
        $this->db->createCommand()
            ->dropColumn('books', 'link')
            ->execute();
        return true;
	}
}
