<?php

namespace frontend\rating\controllers;

use common\models\News;
use yii\web\NotFoundHttpException;
use frontend\rating\components\Controller;

class NewsController extends Controller
{
    public $layout = 'news';

    /**
     * @return string
     */
    public function actionList()
    {
        $pageName = 'Новости';

        $news = News::find()->defaultOrder()->published()->all();

        return $this->render('list', [
            'pageName' => $pageName,
            'news' => $news,
        ]);
    }

    /**
     * @param $slug
     * @return string
     */
    public function actionView($slug)
    {
        $news = News::findOne(['slug' => $slug]);

        if(!$news) {
            throw new NotFoundHttpException();
        }

        return $this->render('view', [
            'news' => $news,
            'pageName' => $news->title,
        ]);
    }
}