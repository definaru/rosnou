<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\Query;

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
class NewsQuery extends ActiveQuery
{
    /**
     * @param $limit
     * @return $this
     */
    public function last($limit)
    {
        return $this
            ->defaultOrder()
            ->limit($limit)
            ->published()
        ;
    }

    /**
     * @return $this
     */
    public function published()
    {
        return $this->where('is_published = true AND publish_date IS NOT NULL');
    }

    /**
     * @return $this
     */
    public function defaultOrder()
    {
        return $this->orderBy('publish_date DESC, id DESC');
    }
}
