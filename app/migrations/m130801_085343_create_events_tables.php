<?php

use yii\db\mysql\Schema;

class m130801_085343_create_events_tables extends \yii\db\Migration
{
	public function up()
	{
        $this->db->createCommand()->createTable('events', array(
            'id'          => Schema::TYPE_PK,
            'start_date'  => Schema::TYPE_DATE,
            'start_time'  => Schema::TYPE_TIME,
            'end_date'    => Schema::TYPE_DATE,
            'end_time'    => Schema::TYPE_TIME,
            'type'        => Schema::TYPE_SMALLINT . ' default 1',
            'title'       => Schema::TYPE_STRING,
            'description' => Schema::TYPE_STRING,
            'user_id'     => Schema::TYPE_INTEGER,
        ))->execute();

        $this->db->createCommand()->createTable('user_events', array(
            'id'       => Schema::TYPE_PK,
            'user_id'  => Schema::TYPE_INTEGER,
            'event_id' => Schema::TYPE_INTEGER,
        ))->execute();

        $this->db->createCommand()
            ->addForeignKey('user_id_fk_in_events', 'events', 'user_id', 'users', 'id')
            ->execute();

        $this->db->createCommand()
            ->addForeignKey('user_id_fk_in_user_events', 'user_events', 'user_id', 'users', 'id')
            ->execute();

        $this->db->createCommand()
            ->addForeignKey('event_id_fk_in_user_events', 'user_events', 'event_id', 'events', 'id')
            ->execute();

	}

	public function down()
	{
        $this->db->createCommand()
            ->dropForeignKey('user_id_fk_in_events', 'events')
            ->execute();

        $this->db->createCommand()
            ->dropForeignKey('user_id_fk_in_user_events', 'user_events')
            ->execute();

        $this->db->createCommand()
            ->dropForeignKey('event_id_fk_in_user_events', 'user_events')
            ->execute();

        $this->db->createCommand()->dropTable('user_events')->execute();

        $this->db->createCommand()->dropTable('events')->execute();

		return true;
	}
}
