<?php

use yii\db\Migration;

class m170707_084754_create_table_user extends Migration
{
    public function up()
    {
        if(!Yii::$app->db->schema->getTableSchema('{{%user}}')) {
            $this->execute("CREATE TABLE {{%user}}
                (
                  id serial NOT NULL,
                  email character varying NOT NULL, -- Email
                  firstname character varying NOT NULL, -- Имя
                  middlename character varying NOT NULL, -- Отчество
                  lastname character varying NOT NULL, -- Фамилия
                  orgname character varying NOT NULL, -- Оргнизация
                  \"position\" character varying NOT NULL, -- Должность
                  password_hash character varying NOT NULL, -- Хеш пароля
                  password_reset_token character varying, -- Токен сброса пароля
                  created_timestamp timestamp without time zone DEFAULT now(), -- Метка создания
                  login character varying NOT NULL, -- Логин
                  CONSTRAINT {{%user_pkey}} PRIMARY KEY (id),
                  CONSTRAINT {{%user_email_key}} UNIQUE (email)
                )
                WITH (
                  OIDS=FALSE
                );");

            $this->execute("COMMENT ON COLUMN {{%user.email}} IS 'Email';");
            $this->execute("COMMENT ON COLUMN {{%user.firstname}} IS 'Имя';");
            $this->execute("COMMENT ON COLUMN {{%user.middlename}} IS 'Отчество';");
            $this->execute("COMMENT ON COLUMN {{%user.lastname}} IS 'Фамилия';");
            $this->execute("COMMENT ON COLUMN {{%user.orgname}} IS 'Оргнизация';");
            $this->execute("COMMENT ON COLUMN {{%user.position}} IS 'Должность';");
            $this->execute("COMMENT ON COLUMN {{%user.password_hash}} IS 'Хеш пароля';");
            $this->execute("COMMENT ON COLUMN {{%user.password_reset_token}} IS 'Токен сброса пароля';");
            $this->execute("COMMENT ON COLUMN {{%user.created_timestamp}} IS 'Метка создания';");
            $this->execute("COMMENT ON COLUMN {{%user.login}} IS 'Логин';");
        }
    }

    public function down()
    {
        echo "m170707_084754_create_table_user cannot be reverted.\n";

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
