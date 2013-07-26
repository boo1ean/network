<?php

use yii\db\mysql\Schema;

class m130726_125635_alter_books_drop_add_columns extends \yii\db\Migration
{
	public function up()
	{
        $this->db->createCommand()
            ->alterColumn('books', 'type', Schema::TYPE_SMALLINT . ' default 1')
            ->execute();

        $this->db->createCommand()->dropColumn('books', 'tags')->execute();

        $this->db->createCommand()->addColumn('books', 'tag_id', Schema::TYPE_INTEGER)->execute();

        $this->db->createCommand()->addColumn('books', 'description', Schema::TYPE_STRING)->execute();
	}

	public function down()
	{
        $this->db->createCommand()->addColumn('books', 'tags', Schema::TYPE_STRING)->execute();

        $this->db->createCommand()->dropColumn('books', 'tag_id')->execute();

        $this->db->createCommand()->dropColumn('books', 'description')->execute();

		return true;
	}
}
