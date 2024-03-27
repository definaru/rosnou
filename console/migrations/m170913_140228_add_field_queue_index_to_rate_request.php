<?php

use yii\db\Migration;

class m170913_140228_add_field_queue_index_to_rate_request extends Migration
{
    public function up()
    {
        $this->addColumn('{{%rate_request}}', 'queue_index', 'integer');
    }

    public function down()
    {
        echo "m170913_140228_add_field_queue_index_to_rate_request cannot be reverted.\n";

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
