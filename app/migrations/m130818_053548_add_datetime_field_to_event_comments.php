<?php

use yii\db\Schema;

class m130818_053548_add_datetime_field_to_event_comments extends \yii\db\Migration
{
	public function up()
	{
        $this->db->createCommand()
            ->addColumn('event_comments', 'post_datetime', Schema::TYPE_TIMESTAMP . ' default CURRENT_TIMESTAMP')
            ->execute();
        return true;
	}

	public function down()
	{
        $this->db->createCommand()
            ->dropColumn('event_comments', 'post_datetime')
            ->execute();
        return true;
	}
}
