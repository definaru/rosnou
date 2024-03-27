<?php

use yii\db\Migration;

class m170714_070919_add_field_email_verified_to_user_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'email_verified', 'BOOLEAN NOT NULL DEFAULT FALSE');
    }

    public function down()
    {
        echo "m170714_070919_add_field_email_verified_to_user_table cannot be reverted.\n";

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
