<?php

use yii\db\mysql\Schema;

class m130716_143501_add_default_value_for_user_type extends \yii\db\Migration
{
	public function up()
	{
        $this->db->createCommand()
            ->alterColumn('users', 'type', Schema::TYPE_SMALLINT . ' default 1')
            ->execute();
	}

	public function down()
	{
        $this->db->createCommand()
            ->alterColumn('users', 'type', Schema::TYPE_SMALLINT)
            ->execute();
		return true;
	}
}
