<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%news}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property string $preview
 * @property string $content
 * @property string $publish_date
 * @property boolean $is_published
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property integer $views_count
 * @property string $created_at
 * @property string $updated_at
 */
class News extends \yii\db\ActiveRecord
{

    public static function find()
    {
        return new NewsQuery(News::class);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%news}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'slug', 'preview', 'content', 'publish_date'], 'required'],
            [['title', 'slug', 'preview', 'content', 'meta_title', 'meta_keywords', 'meta_description'], 'string'],
            [['publish_date', 'created_at', 'updated_at'], 'safe'],
            [['is_published'], 'boolean'],
        ];
    }

    

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'slug' => 'Адрес',
            'preview' => 'Превью',
            'content' => 'Содержание',
            'publish_date' => 'Дата публикации',
            'is_published' => 'Опубликовано',
            'meta_title' => 'Meta Title',
            'meta_keywords' => 'Meta Keywords',
            'meta_description' => 'Meta Description',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }
}
