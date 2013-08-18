<?php

use yii\db\Schema;

class m130818_050359_create_event_comments_table extends \yii\db\Migration
{
	public function up()
	{
        $this->db->createCommand()->createTable('event_comments', array(
            'id'       => Schema::TYPE_PK,
            'event_id' => Schema::TYPE_INTEGER,
            'user_id'  => Schema::TYPE_INTEGER,
            'body'     => Schema::TYPE_STRING
        ))->execute();

        $this->db->createCommand()
            ->addForeignKey('user_id_fk_in_event_comments', 'event_comments', 'user_id', 'users', 'id')
            ->execute();

        $this->db->createCommand()
            ->addForeignKey('event_id_fk_in_event_comments', 'event_comments', 'event_id', 'events', 'id')
            ->execute();
	}

	public function down()
	{
        $this->db->createCommand()
            ->dropForeignKey('user_id_fk_in_event_comments', 'event_comments')
            ->execute();

        $this->db->createCommand()
            ->dropForeignKey('event_id_fk_in_event_comments', 'event_comments')
            ->execute();

        $this->db->createCommand()->dropTable('event_comments')->execute();

		return true;
	}
}
