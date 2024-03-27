<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\RateRequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Запросы на участие';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rate-request-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'site_id',
            'period_id',
            'status_index',
            'expert_id',
            // 'created_datetime',
            // 'score',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{view}'],
        ],
    ]); ?>
</div>
