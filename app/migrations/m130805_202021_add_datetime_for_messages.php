<?php

use yii\db\Schema;

class m130805_202021_add_datetime_for_messages extends \yii\db\Migration
{
	public function up()
	{
        $this->db->createCommand()
            ->addColumn('messages', 'datetime', Schema::TYPE_TIMESTAMP . ' default CURRENT_TIMESTAMP')
            ->execute();
        return true;
	}

	public function down()
	{
        $this->db->createCommand()
            ->dropColumn('messages', 'datetime')
            ->execute();
        return true;
	}
}
