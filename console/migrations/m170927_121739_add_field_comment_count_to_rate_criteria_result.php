<?php

use yii\db\Migration;

class m170927_121739_add_field_comment_count_to_rate_criteria_result extends Migration
{
    public function up()
    {
        $this->addColumn('{{%rate_criteria_result}}', 'comment_count', 'integer not null DEFAULT 0');
    }

    public function down()
    {
        echo "m170927_121739_add_field_comment_count_to_rate_criteria_result cannot be reverted.\n";

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
