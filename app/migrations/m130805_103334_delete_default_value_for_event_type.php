<?php

use yii\db\mysql\Schema;

class m130805_103334_delete_default_value_for_event_type extends \yii\db\Migration
{
	public function up()
	{
        $this->db->createCommand()
            ->alterColumn('events', 'type', Schema::TYPE_SMALLINT)
            ->execute();
	}

	public function down()
	{
        $this->db->createCommand()
            ->alterColumn('events', 'type', Schema::TYPE_SMALLINT . ' default 1')
            ->execute();
		return true;
	}
}
