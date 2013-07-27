<?php

use yii\db\mysql\Schema;

class m130727_125408_create_tables_for_library extends \yii\db\Migration
{
	public function up()
	{
        $this->db->createCommand()->createTable('books', array(
            'id' => Schema::TYPE_PK,
            'author' => Schema::TYPE_STRING,
            'title' => Schema::TYPE_STRING,
            'description' => Schema::TYPE_STRING,
            'type' => Schema::TYPE_SMALLINT . ' default 1',
            'status' => Schema::TYPE_STRING,
        ))->execute();

        $this->db->createCommand()->createTable('tags', array(
            'id' => Schema::TYPE_PK,
            'title' => Schema::TYPE_STRING,
        ))->execute();

        $this->db->createCommand()->createTable('book_tags', array(
            'id' => Schema::TYPE_PK,
            'book_id' => Schema::TYPE_INTEGER,
            'tag_id' => Schema::TYPE_INTEGER,
        ))->execute();

        $this->db->createCommand()
            ->addForeignKey('book_id_fk_in_book_tags', 'book_tags', 'book_id', 'books', 'id')
            ->execute();

        $this->db->createCommand()
            ->addForeignKey('tag_id_fk_in_book_tags', 'book_tags', 'tag_id', 'tags', 'id')
            ->execute();

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
        $this->db->createCommand()
            ->dropForeignKey('book_id_fk_in_book_tags', 'book_tags')
            ->execute();

        $this->db->createCommand()
            ->dropForeignKey('tag_id_fk_in_book_tags', 'book_tags')
            ->execute();

        $this->db->createCommand()
            ->dropForeignKey('book_id_fk_in_book_taking', 'book_taking')
            ->execute();

        $this->db->createCommand()
            ->dropForeignKey('user_id_fk_in_book_taking', 'book_taking')
            ->execute();

        $this->db->createCommand()->dropTable('books')->execute();

        $this->db->createCommand()->dropTable('tags')->execute();

        $this->db->createCommand()->dropTable('book_tags')->execute();

        $this->db->createCommand()->dropTable('book_taking')->execute();

		return true;
	}
}
