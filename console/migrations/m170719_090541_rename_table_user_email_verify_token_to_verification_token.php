<?php

use yii\db\Migration;

class m170719_090541_rename_table_user_email_verify_token_to_verification_token extends Migration
{
    public function up()
    {
        $this->renameTable('{{%user_email_verify_token}}', '{{%verification_token}}');
        $this->truncateTable('{{%verification_token}}');
        $this->addColumn('{{%verification_token}}', 'type', 'smallint NOT NULL CHECK (type > 0)');
    }

    public function down()
    {
        echo "m170719_090541_rename_table_user_email_verify_token_to_verification_token cannot be reverted.\n";

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
