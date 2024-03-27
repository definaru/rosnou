<?php

use yii\db\Migration;

class m171002_124428_add_field_queue_index_to_site_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%site}}', 'queue_index', 'integer');
    }

    public function down()
    {
        echo "m171002_124428_add_field_queue_index_to_site_table cannot be reverted.\n";

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
