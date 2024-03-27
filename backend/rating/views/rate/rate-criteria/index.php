<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\RateCriteriaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Критерии для типа "'. $criteriaType->title . '"';
$this->params['breadcrumbs'][] = ['label' => 'Периоды', 'url' => ['/rate/rate-period']];
$this->params['breadcrumbs'][] = ['label' => 'Типы критериев', 'url' => ['/rate/rate-criteria-type', 'id' => $criteriaType->period_id]];
$this->params['breadcrumbs'][] = 'Критерии';
?>
<div class="rate-criteria-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить критерий', ['create', 'type' => $criteriaType->id], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'score',
            'created_datetime',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>
</div>
