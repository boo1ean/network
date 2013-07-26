<?php

use yii\db\mysql\Schema;

class m130726_153444_create_book_tags_table extends \yii\db\Migration
{
	public function up()
	{
        $this->db->createCommand()
            ->dropForeignKey('tag_id_fk_in_books', 'books')
            ->execute();

        $this->db->createCommand()
            ->dropColumn('books', 'tag_id')
            ->execute();

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

	}

	public function down()
	{
        $this->db->createCommand()
            ->addColumn('books', 'tag_id', Schema::TYPE_INTEGER)
            ->execute();

        $this->db->createCommand()
            ->addForeignKey('tag_id_fk_in_books', 'books', 'tag_id', 'tags', 'id')
            ->execute();

        $this->db->createCommand()->dropTable('book_tags')->execute();

        $this->db->createCommand()
            ->dropForeignKey('book_id_fk_in_book_tags', 'book_tags')
            ->execute();

        $this->db->createCommand()
            ->dropForeignKey('book_id_fk_in_book_tags', 'book_tags')
            ->execute();

		return true;
	}
}
