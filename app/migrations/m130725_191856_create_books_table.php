<?php

use yii\db\mysql\Schema;

class m130725_191856_create_books_table extends \yii\db\Migration
{
	public function up()
	{
        $this->db->createCommand()->createTable('books', array(
            'id' => Schema::TYPE_PK,
            'author' => Schema::TYPE_STRING,
            'title' => Schema::TYPE_STRING,
            'type' => Schema::TYPE_STRING,
            'tags' => Schema::TYPE_STRING,
            'status' => Schema::TYPE_STRING,
        ))->execute();
	}

	public function down()
	{
        $this->db->createCommand()->dropTable('books')->execute();
		return true;
	}
}
