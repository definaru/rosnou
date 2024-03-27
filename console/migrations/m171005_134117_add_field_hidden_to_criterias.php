<?php

use yii\db\Migration;

class m171005_134117_add_field_hidden_to_criterias extends Migration
{
    public function up()
    {
        $this->addColumn('{{%rate_criteria}}', 'hidden', 'boolean DEFAULT false');
    }

    public function down()
    {
        echo "m171005_134117_add_field_hidden_to_criterias cannot be reverted.\n";

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
