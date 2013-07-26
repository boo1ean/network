<?php

use yii\db\mysql\Schema;

class m130726_144035_create_tags_table extends \yii\db\Migration
{
	public function up()
	{
        $this->db->createCommand()->createTable('tags', array(
            'id' => Schema::TYPE_PK,
            'title' => Schema::TYPE_STRING,
        ))->execute();

        $this->db->createCommand()
            ->addForeignKey('tag_id_fk_in_books', 'books', 'tag_id', 'tags', 'id')
            ->execute();
	}

	public function down()
	{
        $this->db->createCommand()->dropTable('tags')->execute();
        return true;
	}
}
