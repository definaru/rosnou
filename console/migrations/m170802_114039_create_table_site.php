<?php

use yii\db\Migration;

class m170802_114039_create_table_site extends Migration
{
    public function up()
    {
        $this->execute("DROP TABLE IF EXISTS {{%site}}");

        $this->execute(" 
            CREATE TABLE {{%site}}
            (
              id serial NOT NULL,
              type_id integer, -- Тип сайта
              domain character varying NOT NULL, -- Домен сайта
              title character varying NOT NULL, -- Название
              status_index smallint NOT NULL DEFAULT 0::smallint, -- Статус сайта ( свободная заявка, ожидает экспертизу, прошел экспертизу )
              user_id integer NOT NULL, -- Владелец сайта (автор заявки)
              created_timestamp timestamp without time zone NOT NULL DEFAULT now(), -- Дата подачи заявки
              org_title character varying, -- Полное название учреждения
              district_id integer, -- Федеральный округ
              subject_id integer, -- Субъект РФ
              location character varying, -- Населенный пункт
              headname character varying, -- ФИО руководителя
              CONSTRAINT {{%site_pkey}} PRIMARY KEY (id),
              CONSTRAINT {{%site_district_id_fkey}} FOREIGN KEY (district_id)
                  REFERENCES {{%site_district}} (id) MATCH SIMPLE
                  ON UPDATE NO ACTION ON DELETE NO ACTION,
              CONSTRAINT {{%site_subject_id_fkey}} FOREIGN KEY (subject_id)
                  REFERENCES {{%site_subject}} (id) MATCH SIMPLE
                  ON UPDATE NO ACTION ON DELETE NO ACTION,
              CONSTRAINT {{%site_type_id_fkey}} FOREIGN KEY (type_id)
                  REFERENCES {{%site_type}} (id) MATCH SIMPLE
                  ON UPDATE NO ACTION ON DELETE NO ACTION,
              CONSTRAINT {{%site_user_id_fkey}} FOREIGN KEY (user_id)
                  REFERENCES {{%user}} (id) MATCH SIMPLE
                  ON UPDATE NO ACTION ON DELETE NO ACTION
            )
            WITH (
              OIDS=FALSE
            );
        ");

        $this->execute("COMMENT ON COLUMN {{%site.type_id}} IS 'Тип сайта';");
        $this->execute("COMMENT ON COLUMN {{%site.domain}} IS 'Домен сайта';");
        $this->execute("COMMENT ON COLUMN {{%site.title}} IS 'Название';");
        $this->execute("COMMENT ON COLUMN {{%site.status_index}} IS 'Статус сайта ( свободная заявка, ожидает экспертизу, прошел экспертизу )';");
        $this->execute("COMMENT ON COLUMN {{%site.user_id}} IS 'Владелец сайта (автор заявки)';");
        $this->execute("COMMENT ON COLUMN {{%site.created_timestamp}} IS 'Дата подачи заявки';");
        $this->execute("COMMENT ON COLUMN {{%site.org_title}} IS 'Полное название учреждения';");
        $this->execute("COMMENT ON COLUMN {{%site.district_id}} IS 'Федеральный округ';");
        $this->execute("COMMENT ON COLUMN {{%site.subject_id}} IS 'Субъект РФ';");
        $this->execute("COMMENT ON COLUMN {{%site.location}} IS 'Населенный пункт';");
        $this->execute("COMMENT ON COLUMN {{%site.headname}} IS 'ФИО руководителя';");
    }

    public function down()
    {
        echo "m170802_114039_create_table_site cannot be reverted.\n";

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
