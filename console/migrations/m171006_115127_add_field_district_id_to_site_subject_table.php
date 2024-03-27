<?php

use yii\db\Migration;

class m171006_115127_add_field_district_id_to_site_subject_table extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE {{%site_subject}} ADD district_id integer DEFAULT 1 NULL");
        $this->execute("ALTER TABLE {{%site_subject}}
        ADD CONSTRAINT {{%site_subject_site_district_id_fk}}
        FOREIGN KEY (district_id) REFERENCES {{%site_district}} (id)");

        $this->execute("ALTER TABLE {{%site_subject}} ALTER COLUMN district_id DROP DEFAULT");
        $this->execute("ALTER TABLE {{%site_subject}} ALTER COLUMN district_id SET NOT NULL");
    }

    public function down()
    {
        echo "m171006_115127_add_field_district_id_to_site_subject_table cannot be reverted.\n";

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
