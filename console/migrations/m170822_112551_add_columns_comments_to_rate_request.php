<?php

use yii\db\Migration;

class m170822_112551_add_columns_comments_to_rate_request extends Migration
{
    public function up()
    {
        $this->addColumn('{{%rate_request}}', 'moderator_comment', 'VARCHAR');
        $this->addColumn('{{%rate_request}}', 'user_comment', 'VARCHAR');
    }

    public function down()
    {
        echo "m170822_112551_add_columns_comments_to_rate_request cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
