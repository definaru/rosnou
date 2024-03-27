<?php

namespace frontend\rating\managers;

use \common\models\Site;
use \common\models\SiteType;
use \common\models\RatePeriod;
use \common\models\RateRequest;
use \common\models\RateCriteria;
use \common\models\RateCriteriaResult;
use \common\models\RateCriteriaTypeResult;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;

class ScoreRulesManager
{

    // Правила
    private $rules = [

        1 => [
            'max' => 100,
            'rules' => [
/*                0 => [
                    'title' => 'участник рейтинга',
                    'color_class' => 'lvl0',
                    'color' => 'grey',
                    'score_title' => '0 баллов',
                    'score_min' => 0,
                    'score_max' => 0,
                    'image' => 'member.png',
                ],*/
                1 => [
                    'title' => 'участник рейтинга',
                    'color_class' => 'lvl5',
                    'color' => 'grey',
                    'score_title' => 'меньше 49 баллов',
                    'score_min' => 0,
                    'score_max' => 49.9,
                    'image' => 'member.png',
                    'diplom_class' => 'var5',
                ],
                2 => [
                    'title' => 'перспективный сайт',
                    'color_class' => 'lvl4',
                    'color' => 'yellow',
                    'score_title' => '69 &ndash; 50 баллов',
                    'score_min' => 50,
                    'score_max' => 69.9,
                    'image' => 'bronze.png',
                    'diplom_class' => 'var4',
                ],
                3 => [
                    'title' => 'хороший сайт',
                    'color_class' => 'lvl3',
                    'color' => 'blue',
                    'score_title' => '89 &ndash; 70 баллов',
                    'score_min' => 70,
                    'score_max' => 89.9,
                    'image' => 'silver.png',
                    'diplom_class' => 'var3',
                ],
                4 => [
                    'title' => 'отличный сайт',
                    'color_class' => 'lvl2',
                    'color' => 'green',
                    'score_title' => '99 &ndash; 90 баллов',
                    'score_min' => 90,
                    'score_max' => 99.9,
                    'image' => 'gold.png',
                    'diplom_class' => 'var2',
                ],
                5 => [
                    'title' => 'победитель',
                    'color_class' => 'lvl1',
                    'color' => 'red',
                    'score_title' => '100 баллов',
                    'score_min' => 100,
                    'score_max' => 200,
                    'image' => 'winner.png',
                    'diplom_class' => 'var1',
                ],
            ],
        ],

        2 => [
            'max' => 50,
            'rules' => [

/*                0 => [
                    'title' => 'участник рейтинга',
                    'color_class' => 'lvl0',
                    'color' => 'grey',
                    'score_title' => '0 баллов',
                    'score_min' => 0,
                    'score_max' => 0,
                    'image' => 'member.png',
                ],*/
                1 => [
                    'title' => 'участник рейтинга',
                    'color_class' => 'lvl5',
                    'color' => 'grey',
                    'score_title' => 'меньше 25 баллов',
                    'score_min' => 0,
                    'score_max' => 24.9,
                    'image' => 'member.png',
                    'diplom_class' => 'var5',
                ],
                2 => [
                    'title' => 'перспективный сайт',
                    'color_class' => 'lvl4',
                    'color' => 'yellow',
                    'score_title' => '34.5 &ndash; 25 баллов',
                    'score_min' => 25,
                    'score_max' => 34.9,
                    'image' => 'bronze.png',
                    'diplom_class' => 'var4',
                ],
                3 => [
                    'title' => 'хороший сайт',
                    'color_class' => 'lvl3',
                    'color' => 'blue',
                    'score_title' => '44.5 &ndash; 35 баллов',
                    'score_min' => 35,
                    'score_max' => 44.9,
                    'image' => 'silver.png',
                    'diplom_class' => 'var3',
                ],
                4 => [
                    'title' => 'отличный сайт',
                    'color_class' => 'lvl2',
                    'color' => 'green',
                    'score_title' => '49.5 &ndash; 45 баллов',
                    'score_min' => 45,
                    'score_max' => 49.9,
                    'image' => 'gold.png',
                    'diplom_class' => 'var2',
                ],
                5 => [
                    'title' => 'победитель',
                    'color_class' => 'lvl1',
                    'color' => 'red',
                    'score_title' => '50 баллов',
                    'score_min' => 50,
                    'score_max' => 100,
                    'image' => 'winner.png',
                    'diplom_class' => 'var1',
                ],
            ]
        ],
        3 => [
            'max' => 15,
            'rules' => [

                1 => [
                    'title' => 'участник рейтинга',
                    'color_class' => 'lvl5',
                    'color' => 'grey',
                    'score_title' => '0.5 &ndash; 9.5 баллов',
                    'score_min' => 0,
                    'score_max' => 9.9,
                    'image' => 'member.png',
                    'diplom_class' => 'var5',
                ],
                2 => [
                    'title' => 'отличный сайт',
                    'color_class' => 'lvl2',
                    'color' => 'green',
                    'score_title' => '10 &ndash; 14.5 баллов',
                    'score_min' => 10,
                    'score_max' => 14.9,
                    'image' => 'gold.png',
                    'diplom_class' => 'var2',
                ],
                3 => [
                    'title' => 'победитель',
                    'color_class' => 'lvl1',
                    'color' => 'red',
                    'score_title' => '15 баллов',
                    'score_min' => 15,
                    'score_max' => 50,
                    'image' => 'winner.png',
                    'diplom_class' => 'var1',
                ],
            ]
        ]

    ];

    /*
    * Возвращает максимально количество баллов рейтинга (по $SiteType->score_type_id)
    */
    public function getMaxScore(RatePeriod $Period, int $score_type_id) {

        $scores = $this->scoreByTypes($Period, [$score_type_id]);
        return $scores[$score_type_id] ?? 0;
    }

    /**
     * @param RatePeriod $activePeriod
     * @param array $sites
     * @return array
     */

    public function scoreBySiteTypes(RatePeriod $Period, array $sites){

        // для каких типов формируем массив
        $sitesTypesIds = ArrayHelper::getColumn($sites, 'type_id');
        return $this->scoreByTypes($Period, $sitesTypesIds);
    }

    private function scoreByTypes(RatePeriod $Period, array $sitesTypesIds)
    {

        $criterias = RateCriteria::find()->alias('c')
            ->innerJoinWith('type as t')
            ->select(['c.id', 'c.score', 'c.type_id', 't.site_type_id', 'c.field_type', 'c.field_name'])
            ->andWhere(['t.period_id' => $Period->id])
            ->andWhere(['in', 't.site_type_id', $sitesTypesIds])
            ->createCommand()
            ->queryAll();

        // Собираем суммы по критериям-спискам
        $scores = [];

        foreach($criterias as $criteria) {

            $site_type_id = $criteria['site_type_id'];
            $field_name = $criteria['field_name'] ?? 'common';
            $field_type = (int) $criteria['field_type'];

            if( !isset($scores[$site_type_id][$field_name]) ){
                $scores[$site_type_id][$field_name] = 0;
            }

            // максимальные значение по списковым критериям
            if( $field_type == RateCriteria::CRITERIA_TYPE_LIST ){
                $scores[$site_type_id][$field_name] = max(
                    $scores[$site_type_id][$field_name], 
                    $criteria['score']
                );
            }

            // Сумма по обычным критериям
            else if( $field_type == RateCriteria::CRITERIA_TYPE_YESNO ){
                $scores[$site_type_id][$field_name] += $criteria['score'];
            }
        }

        // Считаем суммы по типам
        $typeScores = [];
        foreach($scores as $siteTypeId => $typeScore){
            $typeScores[$siteTypeId] = array_sum($typeScore);
        }

        return $typeScores;
    }

    /**
     * Извлекаем массив правил для кол-во баллов указанного типа сайтов
     */
    public function getScoreData($score_type_id, $score){

        $rules = $this->rules[$score_type_id]; 

        foreach($rules['rules'] as $rule){
            if( $score >= $rule['score_min'] && $score <= $rule['score_max'] ){
                return $rule;
            }
        }

        return null;
    }

    /**
     * Получаем массив request_id => rules
     * на основе имеющегося массива самообследований
     * массив $Requests желательно извлечь с моделью Site
     * во избежании лишнего запроса на каждую запись
     */
    public function getRules(array $requests){

        $types = SiteType::find()->asArray()->indexBy('id')->all();
        $results = [];

        foreach($requests as $request){
            $type_id = $request['site_type_id'];
            $type = $types[$type_id];
            $results[$request['request_id']] = $this->getScoreData($type['score_type_id'], $request['score']);
        }

        return $results;
    }

}
