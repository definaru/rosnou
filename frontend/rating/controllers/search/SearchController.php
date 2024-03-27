<?php

namespace frontend\rating\controllers\search;

use frontend\rating\managers\SearchManager;
use frontend\rating\forms\SearchForm;
use frontend\rating\components\Controller;

class SearchController extends Controller
{
    public $layout = 'page';

    public function actionList()
    {
        $pageName = 'Поиск';

        $form = new SearchForm;
        $form->attributes = \Yii::$app->request->get('SearchForm');
        
        $SearchManager = \Yii::$container->get(SearchManager::class);
        $list = $SearchManager->search($form);

        return $this->render('list', [ 
            'list' => $list,
            'pageName' => $pageName,
            'model' => $form,
        ]);
    }

}