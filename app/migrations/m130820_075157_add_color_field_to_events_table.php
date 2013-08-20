<?php

use yii\db\Schema;

class m130820_075157_add_color_field_to_events_table extends \yii\db\Migration
{
	public function up()
	{
        $this->db->createCommand()
            ->addColumn('events', 'color', Schema::TYPE_STRING)
            ->execute();
        return true;
	}

	public function down()
	{
        $this->db->createCommand()
            ->dropColumn('events', 'color')
            ->execute();
        return true;
	}
}
