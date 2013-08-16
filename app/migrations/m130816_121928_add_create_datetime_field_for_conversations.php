<?php

use yii\db\Schema;

class m130816_121928_add_create_datetime_field_for_conversations extends \yii\db\Migration
{
    public function up()
    {
        $this->db->createCommand()
            ->addColumn('events', 'create_datetime', Schema::TYPE_TIMESTAMP . ' default CURRENT_TIMESTAMP')
            ->execute();
        return true;
    }

    public function down()
    {
        $this->db->createCommand()
            ->dropColumn('events', 'create_datetime')
            ->execute();
        return true;
    }
}
