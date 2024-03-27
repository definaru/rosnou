<?php

use yii\db\Migration;

class m170906_123929_create_table_rating_criteria_type extends Migration
{
    public function up()
    {
            $this->execute("CREATE TABLE IF NOT EXISTS {{%rate_criteria_type}}
        (
            id serial NOT NULL,
      title character varying NOT NULL, -- Название типа критерия
      site_type_id integer, -- Тип сайта
      period_id integer, -- Период рейтинга
      CONSTRAINT {{%rate_criteria_type_pkey}} PRIMARY KEY (id),
      CONSTRAINT {{%rate_criteria_type_period_id_fkey}} FOREIGN KEY (period_id)
          REFERENCES {{%rate_period}} (id) MATCH SIMPLE
          ON UPDATE NO ACTION ON DELETE NO ACTION,
      CONSTRAINT {{%rate_criteria_type_site_type_id_fkey}} FOREIGN KEY (site_type_id)
          REFERENCES {{%site_type}} (id) MATCH SIMPLE
          ON UPDATE NO ACTION ON DELETE NO ACTION
    )
    WITH (
        OIDS=FALSE
    );");
            $this->execute("COMMENT ON COLUMN {{%rate_criteria_type}}.title IS 'Название типа критерия';");
            $this->execute("COMMENT ON COLUMN {{%rate_criteria_type}}.site_type_id IS 'Тип сайта';");
            $this->execute("COMMENT ON COLUMN {{%rate_criteria_type}}.period_id IS 'Период рейтинга';");
    }

    public function down()
    {
        echo "m170906_123929_create_table_rating_criteria_type cannot be reverted.\n";

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
