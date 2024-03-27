<?php

use yii\db\Migration;

class m170906_124925_create_table_rating_criteria extends Migration
{
    public function up()
    {
        $this->execute("CREATE TABLE IF NOT EXISTS {{%rate_criteria}}
                    (
                      id serial NOT NULL,
                      type_id integer NOT NULL, -- Тип критерия
                      title character varying NOT NULL, -- Название
                      score numeric NOT NULL DEFAULT 0::numeric, -- Баллы
                      created_datetime timestamp without time zone NOT NULL DEFAULT now(),
                      CONSTRAINT {{%rate_criteria_pkey}} PRIMARY KEY (id),
                      CONSTRAINT {{%rate_criteria_type_id_fkey}} FOREIGN KEY (type_id)
                          REFERENCES {{%rate_criteria_type}} (id) MATCH SIMPLE
                          ON UPDATE NO ACTION ON DELETE NO ACTION
                    )
                    WITH (
                      OIDS=FALSE
                    );");

        $this->execute("COMMENT ON TABLE {{%rate_criteria}}
          IS 'Критерий для определенного типа сайта в определенный период проведения рейтинга';");
        $this->execute("COMMENT ON COLUMN {{%rate_criteria}}.type_id IS 'Тип критерия';");
        $this->execute("COMMENT ON COLUMN {{%rate_criteria}}.title IS 'Название';");
        $this->execute("COMMENT ON COLUMN {{%rate_criteria}}.score IS 'Баллы';");
    }

    public function down()
    {
        echo "m170906_123925_create_table_rating_criteria cannot be reverted.\n";

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
