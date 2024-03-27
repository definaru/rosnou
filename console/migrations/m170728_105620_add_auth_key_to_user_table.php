<?php

use yii\db\Migration;

class m170728_105620_add_auth_key_to_user_table extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE {{%user}} ADD COlUMN auth_key VARCHAR(32) NOT NULL DEFAULT ''");
    }

    public function down()
    {
        echo "m170728_105620_add_auth_key_to_user_table cannot be reverted.\n";

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
