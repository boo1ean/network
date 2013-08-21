<?php
use yii\db\Schema;

class m130820_103853_add_user_activity_field extends \yii\db\Migration
{
    public function up()
    {
        $this->db->createCommand()
            ->addColumn('users', 'last_activity', Schema::TYPE_TIMESTAMP . ' ON UPDATE CURRENT_TIMESTAMP default CURRENT_TIMESTAMP')
            ->execute();
        return true;
    }

    public function down()
    {
        $this->db->createCommand()
            ->dropColumn('users', 'last_activity')
            ->execute();
        return true;
    }
}
