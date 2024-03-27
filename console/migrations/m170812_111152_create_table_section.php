<?php

use yii\db\Migration;

class m170812_111152_create_table_section extends Migration
{
    public function up()
    {
        $this->execute("
            CREATE TABLE {{%section}}
            (
              id serial NOT NULL,
              title character varying NOT NULL, -- Название
              parent_id integer, -- id родительского раздела
              route character varying NOT NULL, -- Полный путь до раздела
              body text NOT NULL, -- Текст
              publish_flag smallint NOT NULL DEFAULT 0::smallint, -- Активность
              module character varying NOT NULL, -- Модуль
              created_datetime timestamp without time zone NOT NULL DEFAULT now(),
              CONSTRAINT {{%section_pkey}} PRIMARY KEY (id),
              CONSTRAINT {{%section_parent_id_fkey}} FOREIGN KEY (parent_id)
                  REFERENCES {{%section}} (id) MATCH SIMPLE
                  ON UPDATE NO ACTION ON DELETE NO ACTION,
              CONSTRAINT {{%section_route_key}} UNIQUE (route)
            )
            WITH (
              OIDS=FALSE
            );
        ");

        $this->execute("COMMENT ON TABLE {{%section}} IS 'Раздел на сайте'");
        $this->execute("COMMENT ON COLUMN {{%section}}.title IS 'Название'");
        $this->execute("COMMENT ON COLUMN {{%section}}.parent_id IS 'id родительского раздела'");
        $this->execute("COMMENT ON COLUMN {{%section}}.route IS 'Полный путь до раздела'");
        $this->execute("COMMENT ON COLUMN {{%section}}.body IS 'Текст'");
        $this->execute("COMMENT ON COLUMN {{%section}}.publish_flag IS 'Активность'");
        $this->execute("COMMENT ON COLUMN {{%section}}.module IS 'Модуль'");
    }

    public function down()
    {
        echo "m170812_111152_create_table_section cannot be reverted.\n";

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
