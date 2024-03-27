<?php

use yii\db\Migration;

class m170816_134727_add_avatar_field_to_user_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'avatar_image', 'VARCHAR DEFAULT NULL');
    }

    public function down()
    {
        echo "m170816_134727_add_avatar_field_to_user_table cannot be reverted.\n";

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
