<?php

use yii\db\Schema;

class m130812_140005_add_unread_field_for_user_events extends \yii\db\Migration
{
	public function up()
	{
        $this->db->createCommand()
            ->addColumn('user_events', 'unread', Schema::TYPE_SMALLINT . ' not null default 0')
            ->execute();
        return true;
	}

	public function down()
	{
        $this->db->createCommand()
            ->dropColumn('user_events', 'unread')
            ->execute();
        return true;
	}
}
