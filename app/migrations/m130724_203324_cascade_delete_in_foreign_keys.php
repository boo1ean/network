<?php

class m130724_203324_cascade_delete_in_foreign_keys extends \yii\db\Migration
{
	public function up()
	{
        $this->db->createCommand()
            ->dropForeignKey('user_id_fk_in_user_conversations', 'user_conversations')
            ->execute();

        $this->db->createCommand()
            ->dropForeignKey('conversation_id_fk_in_user_conversations', 'user_conversations')
            ->execute();

        $this->db->createCommand()
            ->dropForeignKey('user_id_fk_in_messages', 'messages')
            ->execute();

        $this->db->createCommand()
            ->dropForeignKey('conversation_id_fk_in_messages', 'messages')
            ->execute();

        $this->db->createCommand()
            ->addForeignKey('user_id_fk_in_user_conversations', 'user_conversations', 'user_id', 'users', 'id', 'cascade', 'cascade')
            ->execute();

        $this->db->createCommand()
            ->addForeignKey('conversation_id_fk_in_user_conversations', 'user_conversations', 'conversation_id', 'conversations', 'id', 'cascade', 'cascade')
            ->execute();

        $this->db->createCommand()
            ->addForeignKey('user_id_fk_in_messages', 'messages', 'user_id', 'users', 'id', 'cascade', 'cascade')
            ->execute();

        $this->db->createCommand()
            ->addForeignKey('conversation_id_fk_in_messages', 'messages', 'conversation_id', 'conversations', 'id', 'cascade', 'cascade')
            ->execute();
	}

	public function down()
	{
        $this->db->createCommand()
            ->dropForeignKey('user_id_fk_in_user_conversations', 'user_conversations')
            ->execute();

        $this->db->createCommand()
            ->dropForeignKey('conversation_id_fk_in_user_conversations', 'user_conversations')
            ->execute();

        $this->db->createCommand()
            ->dropForeignKey('user_id_fk_in_messages', 'messages')
            ->execute();

        $this->db->createCommand()
            ->dropForeignKey('conversation_id_fk_in_messages', 'messages')
            ->execute();

        $this->db->createCommand()
            ->addForeignKey('user_id_fk_in_user_conversations', 'user_conversations', 'user_id', 'users', 'id')
            ->execute();

        $this->db->createCommand()
            ->addForeignKey('conversation_id_fk_in_user_conversations', 'user_conversations', 'conversation_id', 'conversations', 'id')
            ->execute();

        $this->db->createCommand()
            ->addForeignKey('user_id_fk_in_messages', 'messages', 'user_id', 'users', 'id')
            ->execute();

        $this->db->createCommand()
            ->addForeignKey('conversation_id_fk_in_messages', 'messages', 'conversation_id', 'conversations', 'id')
            ->execute();

		return true;
	}
}
