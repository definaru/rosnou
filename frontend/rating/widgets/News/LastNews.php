<?php

namespace frontend\rating\widgets\News;

use common\models\News;
use yii\base\Widget;

class LastNews extends Widget
{
    public function run()
    {
        $news = News::find()->last(6)->all();

        $mainNews = array_slice($news, 0, 2);
        $others = array_slice($news, 2, 4);

        return $this->render('last_news', [
            'mainNews' => $mainNews,
            'others' => $others,
        ]);
    }
}