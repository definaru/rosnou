<?php

use yii\db\Migration;

class m170802_113717_create_table_site_district extends Migration
{
    public function up()
    {
        $this->execute("DROP TABLE IF EXISTS {{%site_district}}");

        $this->execute("
            CREATE TABLE {{%site_district}}
            (
              id serial NOT NULL,
              title character varying NOT NULL,
              CONSTRAINT {{%site_district_pkey}} PRIMARY KEY (id)
            )
            WITH (
              OIDS=FALSE
            );
        ");
    }

    public function down()
    {
        echo "m170802_113717_create_table_site_district cannot be reverted.\n";

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
