<?php

use yii\db\Schema;

class m130722_094357_add_type_field_in_conversation_table extends \yii\db\Migration
{
	public function up()
	{
        $this->db->createCommand()
            ->addColumn('conversations', 'private', Schema::TYPE_SMALLINT . ' not null default 1')
            ->execute();
        return true;
	}

	public function down()
	{
		$this->db->createCommand()
            ->dropColumn('conversations', 'private')
            ->execute();
		return true;
	}
}
