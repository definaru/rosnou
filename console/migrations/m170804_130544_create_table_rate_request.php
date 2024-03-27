<?php

use yii\db\Migration;

class m170804_130544_create_table_rate_request extends Migration
{
    public function up()
    {
        $this->execute("DROP TABLE IF EXISTS {{%rate_request}}");

        $this->execute("
            CREATE TABLE {{%rate_request}}
            (
              id serial NOT NULL,
              site_id integer NOT NULL, -- Сайт
              period_id integer NOT NULL, -- Период проведения рейтинга
              status_index integer NOT NULL DEFAULT 0, -- Статус заявки
              expert_id integer, -- Идентификатор эксперта, изначально пуст
              created_datetime timestamp without time zone NOT NULL DEFAULT now(), -- Дата создания
              score numeric NOT NULL DEFAULT 0::numeric, -- Баллы за экспертизу
              CONSTRAINT {{%rate_period_exam_pkey}} PRIMARY KEY (id),
              CONSTRAINT {{%rate_exam_expert_id_fkey}} FOREIGN KEY (expert_id)
                  REFERENCES {{%user}} (id) MATCH SIMPLE
                  ON UPDATE NO ACTION ON DELETE NO ACTION,
              CONSTRAINT {{%rate_period_exam_period_id_fkey}} FOREIGN KEY (period_id)
                  REFERENCES {{%rate_period}} (id) MATCH SIMPLE
                  ON UPDATE NO ACTION ON DELETE NO ACTION,
              CONSTRAINT {{%rate_period_exam_site_id_fkey}} FOREIGN KEY (site_id)
                  REFERENCES {{%site}} (id) MATCH SIMPLE
                  ON UPDATE NO ACTION ON DELETE NO ACTION
            )
            WITH (
              OIDS=FALSE
            );
        ");

        $this->execute("COMMENT ON TABLE {{%rate_request}} IS 'Заявка на самообследование';");
        $this->execute("COMMENT ON COLUMN {{%rate_request.site_id}} IS 'Сайт';");
        $this->execute("COMMENT ON COLUMN {{%rate_request.period_id}} IS 'Период проведения рейтинга';");
        $this->execute("COMMENT ON COLUMN {{%rate_request.status_index}} IS 'Статус заявки';");
        $this->execute("COMMENT ON COLUMN {{%rate_request.expert_id}} IS 'Идентификатор эксперта, изначально пуст';");
        $this->execute("COMMENT ON COLUMN {{%rate_request.created_datetime}} IS 'Дата создания';");
        $this->execute("COMMENT ON COLUMN {{%rate_request.score}} IS 'Баллы за экспертизу';");

        $this->execute("
            CREATE UNIQUE INDEX {{%rate_exam_uni}}
              ON {{%rate_request}}
              USING btree
              (site_id, period_id);
        ");
    }

    public function down()
    {
        echo "m170804_130544_create_table_rate_request cannot be reverted.\n";

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
