<?php

use yii\db\mysql\Schema;

class m130727_065137_create_book_taking_table extends \yii\db\Migration
{
	public function up()
	{
        $this->db->createCommand()->createTable('book_taking', array(
            'id' => Schema::TYPE_PK,
            'book_id' => Schema::TYPE_INTEGER,
            'user_id' => Schema::TYPE_INTEGER,
            'taken' => Schema::TYPE_DATE,
            'returned' => Schema::TYPE_DATE,
        ))->execute();

        $this->db->createCommand()
            ->addForeignKey('book_id_fk_in_book_taking', 'book_taking', 'book_id', 'books', 'id')
            ->execute();

        $this->db->createCommand()
            ->addForeignKey('user_id_fk_in_book_taking', 'book_taking', 'user_id', 'users', 'id')
            ->execute();

	}

	public function down()
	{
        $this->db->createCommand()->dropTable('book_taking')->execute();
        return true;
	}
}
