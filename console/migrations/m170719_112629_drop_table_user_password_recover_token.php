<?php

use yii\db\Migration;

class m170719_112629_drop_table_user_password_recover_token extends Migration
{
    public function up()
    {
        $this->dropTable('{{%user_password_recover_token}}');
    }

    public function down()
    {
        echo "m170719_112629_drop_table_user_password_recover_token cannot be reverted.\n";

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
