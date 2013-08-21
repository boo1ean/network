<?php
use yii\db\mysql\Schema;

class m130821_085102_update_taken_and_returned_to_datetime_format extends \yii\db\Migration
{
    public function up() {
        $this->db->createCommand()
            ->alterColumn('book_taking', 'taken', Schema::TYPE_DATETIME . ' default "0000-00-00 00:00:00"')
            ->execute();

        $this->db->createCommand()
            ->alterColumn('book_taking', 'returned', Schema::TYPE_DATETIME . ' default "0000-00-00 00:00:00"')
            ->execute();
    }

    public function down() {
        $this->db->createCommand()
            ->alterColumn('book_taking', 'taken', Schema::TYPE_DATE . ' default "0000-00-00"')
            ->execute();

        $this->db->createCommand()
            ->alterColumn('book_taking', 'returned', Schema::TYPE_DATE . ' default "0000-00-00"')
            ->execute();
        return true;
    }
}
