<?php

use yii\db\Migration;

class m170802_113925_create_table_site_subject extends Migration
{
    public function up()
    {
        $this->execute("DROP TABLE IF EXISTS {{%site_subject}}");

        $this->execute("
            CREATE TABLE {{%site_subject}}
            (
              id serial NOT NULL,
              title character varying NOT NULL,
              CONSTRAINT {{%site_subject_pkey}} PRIMARY KEY (id)
            )
            WITH (
              OIDS=FALSE
            );
        ");
    }

    public function down()
    {
        echo "m170802_113925_create_table_site_subject cannot be reverted.\n";

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
