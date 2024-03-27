<?php

use yii\db\Migration;

/**
 * Handles the creation for table `news`.
 */
class m170727_082122_create_news_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->execute("DROP TABLE IF EXISTS {{%news}}");

        $this->execute("
          CREATE TABLE {{%news}} (
              id serial PRIMARY KEY,
              title varchar NOT NULL,
              slug varchar NOT NULL UNIQUE,
              h1 varchar NOT NULL,
              preview text NOT NULL,
              content text NOT NULL,
              publish_date timestamp without time zone,
              is_published boolean DEFAULT FALSE NOT NULL,
              meta_title varchar,
              meta_keywords varchar,
              meta_description varchar,
              views_count integer NOT NULL CHECK (views_count >= 0),
              created_at timestamp without time zone DEFAULT now(),
              updated_at timestamp without time zone
          )
        ");
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('news');
    }
}
