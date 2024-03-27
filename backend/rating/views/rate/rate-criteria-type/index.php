<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\RateCriteriaTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = ['label' => 'Периоды', 'url' => ['/rate/rate-period']];
$this->params['breadcrumbs'][] = 'Типы критериев';
?>
<div class="rate-criteria-type-index">

    <h1><?= Html::encode('Типы критериев для периода "'. $period->title . '"') ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить тип критерия', ['create', 'periodId' => $period->id], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            [
                'attribute' => 'site_type_id',
                'value' => function($data) {
                    return $data->siteType->title;
                },
                'filter' => \common\models\SiteType::options(),
            ],
            [
                'format' => 'raw',
                'value' => function($data) {
                    return Html::a('типы критериев', ['rate/rate-criteria', 'type' => $data->id]);
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>
</div>
