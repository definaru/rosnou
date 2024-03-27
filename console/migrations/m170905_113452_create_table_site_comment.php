<?php

use yii\db\Migration;

class m170905_113452_create_table_site_comment extends Migration
{
    public function up()
    {
        $this->execute("CREATE TABLE IF NOT EXISTS {{%site_comment}}
            (
              id serial NOT NULL,
              site_id integer NOT NULL,
              user_id integer NOT NULL,
              body text NOT NULL,
              created_datetime timestamp without time zone NOT NULL DEFAULT '2017-07-20 12:21:57.678052'::timestamp without time zone,
              site_status_index smallint NOT NULL DEFAULT 0::smallint,
              CONSTRAINT {{%site_comment_pkey}} PRIMARY KEY (id),
              CONSTRAINT {{%site_comment_site_id_fkey}} FOREIGN KEY (site_id)
                  REFERENCES {{%site}} (id) MATCH SIMPLE
                  ON UPDATE NO ACTION ON DELETE NO ACTION,
              CONSTRAINT {{%site_comment_user_id_fkey}} FOREIGN KEY (user_id)
                  REFERENCES {{%user}} (id) MATCH SIMPLE
                  ON UPDATE NO ACTION ON DELETE NO ACTION
            )
            WITH (
              OIDS=FALSE
            );");
    }

    public function down()
    {
        echo "m170905_113452_create_table_site_comment cannot be reverted.\n";

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
