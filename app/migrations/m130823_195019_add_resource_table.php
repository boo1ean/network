<?php
use yii\db\Schema;

class m130823_195019_add_resource_table extends \yii\db\Migration
{
    public function up()
    {
        $this->db->createCommand()->createTable('resources', array(
            'id'    => Schema::TYPE_PK,
            'path'  => Schema::TYPE_STRING,
            'link'  => Schema::TYPE_STRING,
        ))->execute();
    }

	public function down()
	{
        $this->db->createCommand()->dropTable('resources')->execute();
        return true;
	}
}
