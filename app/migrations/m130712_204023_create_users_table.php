<?php
use yii\db\mysql\Schema;

class m130712_204023_create_users_table extends \yii\db\Migration
{
	public function up()
	{
        $this->db->createCommand()->createTable('users', array(
            'id' => Schema::TYPE_PK,
            'email' => Schema::TYPE_STRING,
            'first_name' => Schema::TYPE_STRING,
            'last_name' => Schema::TYPE_STRING,
            'password' => Schema::TYPE_STRING,
            'type' => Schema::TYPE_SMALLINT,
        ))->execute();
	}

	public function down()
	{
		$this->db->createCommand()->dropTable('users')->execute();
		return true;
	}
}
