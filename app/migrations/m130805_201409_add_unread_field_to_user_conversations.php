<?php

use yii\db\Schema;

class m130805_201409_add_unread_field_to_user_conversations extends \yii\db\Migration
{
	public function up()
	{
        $this->db->createCommand()
            ->addColumn('user_conversations', 'unread', Schema::TYPE_SMALLINT . ' not null default 0')
            ->execute();
        return true;
	}

	public function down()
	{
        $this->db->createCommand()
            ->dropColumn('user_conversations', 'unread')
            ->execute();
        return true;
	}
}
