<?php

use yii\db\Migration;

class m170907_092746_alter_table_site_field_type_id_not_null extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE {{%site}} ALTER COLUMN type_id SET NOT NULL");
    }

    public function down()
    {
        echo "m170907_092746_alter_table_site_field_type_id_not_null cannot be reverted.\n";

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
