<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\RateCriteria */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Критерии', 'url' => ['index', 'type' => $model->type_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rate-criteria-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Удалить?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'type_id',
            'title',
            'score',
            'created_datetime',
        ],
    ]) ?>

</div>
