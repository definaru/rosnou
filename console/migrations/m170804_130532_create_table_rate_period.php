<?php

use yii\db\Migration;

class m170804_130532_create_table_rate_period extends Migration
{
    public function up()
    {
        $this->execute("DROP TABLE IF EXISTS {{%rate_period}}");

        $this->execute("
            CREATE TABLE {{%rate_period}}
            (
              id serial NOT NULL,
              title character varying NOT NULL, -- Название
              active_flag smallint NOT NULL DEFAULT 0::smallint, -- Активность
              list_order integer NOT NULL DEFAULT 0, -- Порядкок
              created_datetime timestamp without time zone NOT NULL DEFAULT now(),
              CONSTRAINT {{%period_pkey}} PRIMARY KEY (id)
            )
            WITH (
              OIDS=FALSE
            );
        ");

        $this->execute("COMMENT ON TABLE {{%rate_period}}
  IS 'Период проведения рейтинга, активным может быть только один период, порядок нужен чтобы определить последний период, когда нет активных.';");
        $this->execute("COMMENT ON COLUMN {{%rate_period.title}} IS 'Название';");
        $this->execute("COMMENT ON COLUMN {{%rate_period.active_flag}} IS 'Активность';");
        $this->execute("COMMENT ON COLUMN {{%rate_period.list_order}} IS 'Порядкок';");
    }

    public function down()
    {
        echo "m170804_130532_create_table_rate_period cannot be reverted.\n";

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
