<?php

use yii\db\Migration;

class m170913_073919_create_table_rate_criteria_result extends Migration
{
    public function up()
    {
        $this->execute("CREATE TABLE {{%rate_criteria_result}}(
          id serial,
          request_id integer NOT NULL, -- Заявка на самообследование
          status_index smallint NOT NULL DEFAULT 0::smallint, -- Сдан / не сдан
          criteria_id integer NOT NULL, -- Критерий
          created_datetime timestamp without time zone NOT NULL DEFAULT now(),
          url character varying,
          CONSTRAINT {{%rate_exam_score_pkey}} PRIMARY KEY (id),
          CONSTRAINT {{%rate_exam_score_criteria_id_fkey}} FOREIGN KEY (criteria_id)
              REFERENCES {{%rate_criteria}} (id) MATCH SIMPLE
              ON UPDATE NO ACTION ON DELETE NO ACTION,
          CONSTRAINT {{%rate_exam_score_exam_id_fkey}} FOREIGN KEY (request_id)
              REFERENCES {{%rate_request}} (id) MATCH SIMPLE
              ON UPDATE NO ACTION ON DELETE NO ACTION
        )
        WITH (
          OIDS=FALSE
        );");


        $this->execute("COMMENT ON TABLE {{%rate_criteria_result}}
          IS 'Определяет сдан / не сдан критерий указанным сайтом по указанной заявке на самообследование';");
        $this->execute("COMMENT ON COLUMN {{%rate_criteria_result}}.request_id IS 'Заявка на самообследование';");
        $this->execute("COMMENT ON COLUMN {{%rate_criteria_result}}.status_index IS 'Сдан / не сдан';");
        $this->execute("COMMENT ON COLUMN {{%rate_criteria_result}}.criteria_id IS 'Критерий';");


        $this->execute("CREATE UNIQUE INDEX {{%rate_exam_score_uni}}
          ON {{%rate_criteria_result}}
          USING btree
          (request_id, criteria_id);");
    }

    public function down()
    {
        echo "m170913_073919_create_table_rate_criteria_result cannot be reverted.\n";

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
