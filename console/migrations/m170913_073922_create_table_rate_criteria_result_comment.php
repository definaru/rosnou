<?php

use yii\db\Migration;

class m170913_073922_create_table_rate_criteria_result_comment extends Migration
{
    public function up()
    {
        $this->execute("CREATE TABLE IF NOT EXISTS {{%rate_criteria_result_comment}}
            (
              id serial,
              body text NOT NULL, -- Текст
              result_id integer NOT NULL, -- Результат оценки
              created_datetime timestamp without time zone NOT NULL DEFAULT now(), -- Дата создания
              user_id integer NOT NULL,
              CONSTRAINT {{%rate_result_comment_pkey}} PRIMARY KEY (id),
              CONSTRAINT {{%rate_result_comment_result_id_fkey}} FOREIGN KEY (result_id)
                  REFERENCES {{%rate_criteria_result}} (id) MATCH SIMPLE
                  ON UPDATE NO ACTION ON DELETE NO ACTION,
              CONSTRAINT {{%rate_result_comment_user_id_fkey}} FOREIGN KEY (user_id)
                  REFERENCES {{%user}} (id) MATCH SIMPLE
                  ON UPDATE NO ACTION ON DELETE NO ACTION
            )
            WITH (
              OIDS=FALSE
            );");

        $this->execute("COMMENT ON TABLE {{%rate_criteria_result_comment}}
          IS 'Переписка по той или иной оценке эксперта';");
        $this->execute("COMMENT ON COLUMN {{%rate_criteria_result_comment}}.body IS 'Текст';");
        $this->execute("COMMENT ON COLUMN {{%rate_criteria_result_comment}}.result_id IS 'Результат оценки';");
        $this->execute("COMMENT ON COLUMN {{%rate_criteria_result_comment}}.created_datetime IS 'Дата создания';");
    }

    public function down()
    {
        echo "m170913_073922_create_table_rate_criteria_result_comment cannot be reverted.\n";

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
