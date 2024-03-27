<?php

use yii\db\Migration;

class m170714_071639_create_table_user_email_verify_token extends Migration
{
    public function up()
    {
        $this->execute("
            CREATE TABLE {{%user_email_verify_token}} (
                user_id integer not null REFERENCES {{%user}} (id) ON DELETE CASCADE,
                token varchar(40) not null,
                expired TIMESTAMP not null
            );
        ");
    }

    public function down()
    {
        echo "m170714_071639_create_table_user_email_verify_token cannot be reverted.\n";

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
