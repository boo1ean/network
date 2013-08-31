<?php

use yii\db\Schema as Schema;

class m130716_192730_create_tables_for_messaging extends \yii\db\Migration
{
	public function up()
	{
        // Add conversations table
        $this->db->CreateCommand()
            ->CreateTable('conversations', array(
                'id'    => Schema::TYPE_PK,
                'title' => Schema::TYPE_STRING,
            ))->execute();

        // Add user_conversations table
        $this->db->createCommand()
            ->createTable('user_conversations', array(
                'id'                => Schema::TYPE_PK,
                'user_id'           => Schema::TYPE_INTEGER,
                'conversation_id'   => Schema::TYPE_INTEGER,
            ))->execute();

        // Add foreign keys in user_conversations table
        $this->db->createCommand()
            ->addForeignKey('user_id_fk_in_user_conversations', 'user_conversations', 'user_id', 'users', 'id')
            ->addForeignKey('conversation_id_fk_in_user_conversations', 'user_conversations', 'conversation_id', 'conversations', 'id')
            ->execute();

        // Create messages table
        $this->db->createCommand()
            ->createTable('messages', array(
                'id'                => Schema::TYPE_PK,
                'user_id'           => Schema::TYPE_INTEGER,
                'conversation_id'   => Schema::TYPE_INTEGER,
                'body'              => Schema::TYPE_TEXT,
            ))->execute();

        // Add foreign keys in messages table
        $this->db->createCommand()
            ->addForeignKey('user_id_fk_in_messages', 'messages', 'user_id', 'users', 'id')
            ->addForeignKey('conversation_id_fk_in_messages', 'messages', 'conversation_id', 'conversations', 'id')
            ->execute();
	}

	public function down()
	{
        // Drop messages table
        $this->db->createCommand()
            ->dropTable('messages')
            ->execute();
        // Drop user_conversations table
        $this->db->createCommand()
            ->dropTable('user_conversations')
            ->execute();
        // Drop conversations table
        $this->db->createCommand()
            ->dropTable('conversations')
            ->execute();


        return true;
	}
}
