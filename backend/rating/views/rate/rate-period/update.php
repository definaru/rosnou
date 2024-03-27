<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RatePeriod */

$this->title = 'Редактирование периода: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Период', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="rate-period-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
