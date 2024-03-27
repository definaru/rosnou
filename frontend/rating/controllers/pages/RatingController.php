<?php

namespace frontend\rating\controllers\pages;

use yii\helpers\Url;
use frontend\rating\components\Controller;

class RatingController extends Controller
{
    /**
     * @var string
     */
    public $layout = 'page';

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionHistory()
    {
        $pageName = 'История';

        return $this->render('history', [
            'pageName' => $pageName,
        ]);
    }

    public function actionExperts()
    {
        $pageName = 'Эсперты';

        return $this->render('experts', [
            'breadcrumbs' => [$this->rootBreadcrumb, $pageName],
            'pageName' => $pageName,
        ]);
    }

    public function actionSmi()
    {
        $pageName = 'СМИ о рейтинге';

        return $this->render('smi', [
            'breadcrumbs' => [$this->rootBreadcrumb, $pageName],
            'pageName' => $pageName,
        ]);
    }

    public function actionParticipation()
    {
        $pageName = 'Условия участия';

        return $this->render('participation', [
            'breadcrumbs' => [$this->rootBreadcrumb, $pageName],
            'pageName' => $pageName,
        ]);
    }

    public function actionPartners()
    {
        $pageName = 'Информационные партнеры';

        return $this->render('partners', [
            'breadcrumbs' => [$this->rootBreadcrumb, 'Партнеры'],
            'pageName' => $pageName,
        ]);
    }

    public function actionAssignment()
    {
        $pageName = 'Согласие на обработку персональных данных участника Общероссийского рейтинга школьных сайтов';

        return $this->render('assignment', [
            'breadcrumbs' => [
                $this->rootBreadcrumb,
                ['url' => Url::toRoute('o-rejtinge/usloviya-uchastiya'), 'label' => 'Условия участия'],
                'Согласие на обработку персональных данных участника Общероссийского рейтинга школьных сайтов'
            ],
            'pageName' => $pageName,
        ]);
    }
}
