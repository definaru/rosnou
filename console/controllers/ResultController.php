<?php

namespace console\controllers;

use Yii;
use common\managers\SectionManager;
use common\models\RateCriteriaResult;
use common\models\RateCriteriaResultComment;
use common\models\RatePeriod;
use common\models\SiteType;
use domain\Exception;
use frontend\rating\managers\PeriodManager;
use frontend\rating\managers\ScoreRulesManager;
use yii\console\Controller;

class ResultController extends Controller
{
    public function actionCreateMedals($periodId)
    {
        $currentPeriod = RatePeriod::find()->andWhere(['id' => $periodId])->one();

        if(!$currentPeriod) {
            throw new Exception("Current period not found");
        }

        $webroot = realpath(__DIR__ . '/../../frontend/rating/web');
        $periodSlug = $currentPeriod->slug;

        $PeriodManager = new PeriodManager();
        $ScoreRulesManager = new ScoreRulesManager();

        $SiteTypes = SiteType::find()->all();

        foreach($SiteTypes as $Type) {

            $typeSlug = $Type->slug;
            $list = $PeriodManager->loadPeriodSitesSlug($periodSlug, $typeSlug);

            // Извлекаем правила отображения баллов
            $scoreRules = $ScoreRulesManager->getRules($list);

            foreach ($list as $Site){

                $source = $webroot . '/images/pennants/' . $scoreRules[$Site['request_id']]['image'];
                $dest = $webroot . '/images/pennants/m/'. $Site['id'] .'.png';

                echo $source;
                echo "\n";
                echo $dest;
                echo "\n\n\n";

                exec('cp ' . $source . ' ' . $dest, $output);

                print_r($output);
            }
        }
    }
}
