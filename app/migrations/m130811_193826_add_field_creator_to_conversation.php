<?php

use yii\db\Schema;
class m130811_193826_add_field_creator_to_conversation extends \yii\db\Migration
{
    public function up() {
        $this->db->createCommand()
            ->addColumn('conversations', 'creator', Schema::TYPE_INTEGER . ' not null default 0')
            ->execute();
        return true;
    }

    public function down() {
        $this->db->createCommand()
            ->dropColumn('conversations', 'creator')
            ->execute();
        return true;
    }
}
