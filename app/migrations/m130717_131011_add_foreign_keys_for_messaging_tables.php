<?php

class m130717_131011_add_foreign_keys_for_messaging_tables extends \yii\db\Migration
{
	public function up()
	{
        // Add foreign key for user_id in user_conversations table
        $this->db->createCommand()
            ->addForeignKey('user_id_fk_in_user_conversations', 'user_conversations', 'user_id', 'users', 'id')
            ->execute();

        // Add foreign key for user_id in messages table
        $this->db->createCommand()
            ->addForeignKey('user_id_fk_in_messages', 'messages', 'user_id', 'users', 'id')
            ->execute();
	}

	public function down()
	{
        // Delete foreign key for user_id in user_conversations table
        $this->db->createCommand()
            ->dropForeignKey('user_id_fk_in_user_conversations', 'user_conversations')
            ->execute();

        // Delete foreign key for user_id in messages table
        $this->db->createCommand()
            ->dropForeignKey('user_id_fk_in_messages', 'messages')
            ->execute();
        
		return true;
	}
}
