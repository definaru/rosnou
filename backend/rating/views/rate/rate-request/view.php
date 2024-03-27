<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\RateRequest */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Запросы на участие', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rate-request-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'site_id',
            'period_id',
            'status_index',
            'expert_id',
            'created_datetime',
            'score',
        ],
    ]) ?>

</div>
