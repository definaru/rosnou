<?php

use yii\db\Migration;

class m170802_113929_create_table_site_type extends Migration
{
    public function up()
    {
        $this->execute("DROP TABLE IF EXISTS {{%site_type}}");

        $this->execute("
            CREATE TABLE {{%site_type}}
            (
              id serial NOT NULL,
              title character varying NOT NULL, -- Название
              CONSTRAINT {{%site_type_pkey}} PRIMARY KEY (id)
            )
            WITH (
              OIDS=FALSE
            );
        ");

        $this->execute("COMMENT ON COLUMN {{%site_type.title}} IS 'Название';");
    }

    public function down()
    {
        echo "m170802_113929_create_table_site_type cannot be reverted.\n";

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
