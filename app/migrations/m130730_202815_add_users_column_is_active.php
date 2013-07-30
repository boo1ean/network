<?php

use yii\db\mysql\Schema;

class m130730_202815_add_users_column_is_active extends \yii\db\Migration
{
    public function up()
    {
        $this->db->createCommand()
            ->addColumn('users', 'is_active', Schema::TYPE_SMALLINT . ' not null default 0')
            ->execute();
        return true;

    }

    public function down()
    {
        $this->db->createCommand()
            ->dropColumn('users', 'is_active')
            ->execute();
        return true;
    }
}
