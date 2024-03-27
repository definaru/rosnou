<?php

use yii\db\Migration;

class m170818_113946_add_field_headname_email_to_site extends Migration
{
    public function up()
    {
        $this->addColumn('{{%site}}', 'headname_email', 'VARCHAR');
        $this->addColumn('{{%site}}', 'have_ads', 'BOOLEAN NOT NULL DEFAULT false');
    }

    public function down()
    {
        echo "m170818_113946_add_field_headname_email_to_site cannot be reverted.\n";

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
