<?php

use yii\db\mysql\Schema;
class m130716_124541_alter_users_add_settings_column extends \yii\db\Migration
{
	public function up()
	{
        $this->db->createCommand()->addColumn('users', 'settings', Schema::TYPE_BINARY)->execute();
	}

	public function down()
	{
		$this->db->createCommand()->dropColumn('users', 'settings')->execute();
		return true;
	}
}