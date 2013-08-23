<?php
use yii\db\Schema;

class m130823_143625_delete_setting_last_activity_in_users extends \yii\db\Migration
{
	public function up()
	{
        $this->db->createCommand()
            ->alterColumn('users', 'last_activity', Schema::TYPE_TIMESTAMP . ' default CURRENT_TIMESTAMP')
            ->execute();
        return true;
	}

	public function down()
	{
        $this->db->createCommand()
            ->alterColumn('users', 'last_activity', Schema::TYPE_TIMESTAMP . ' ON UPDATE CURRENT_TIMESTAMP default CURRENT_TIMESTAMP')
            ->execute();
        return true;
	}
}
