<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RateCriteria */

$this->title = 'Редактирование критерия: ' . $model->title;

$this->params['breadcrumbs'][] = ['label' => 'Период '. $model->type->period->title, 'url' => ['/rate/rate-period/criteria', 'id' => $model->type->period->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="rate-criteria-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
