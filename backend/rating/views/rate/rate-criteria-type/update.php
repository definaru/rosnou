<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RateCriteriaType */

$this->title = 'Обновление группы: ' . $model->title;

$this->params['breadcrumbs'][] = ['label' => 'Период ' . $model->period->title, 'url' => ['/rate/rate-period/criteria', 'id' => $model->period->id]];
$this->params['breadcrumbs'][] = 'Редактирование группы';
?>
<div class="rate-criteria-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
