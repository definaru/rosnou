<?php

use yii\db\Migration;

class m170810_111042_add_user_role_fields_to_user_table extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE {{%user}} ADD COLUMN is_admin boolean NOT NULL DEFAULT false");
        $this->execute("ALTER TABLE {{%user}} ADD COLUMN is_expert boolean NOT NULL DEFAULT false");
        $this->execute("ALTER TABLE {{%user}} ADD COLUMN is_moderator boolean NOT NULL DEFAULT false");
    }

    public function down()
    {
        echo "m170810_111042_add_user_role_fields_to_user_table cannot be reverted.\n";

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
