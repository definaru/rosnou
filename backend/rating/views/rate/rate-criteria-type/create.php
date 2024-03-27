<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\RateCriteriaType */

$this->title = 'Добавление группы для периода "' . $period->title .'"';

$this->params['breadcrumbs'][] = ['label' => 'Период ' . $period->title, 'url' => ['/rate/rate-period/criteria', 'id' => $period->id]];
$this->params['breadcrumbs'][] = 'Добавление группы';
?>
<div class="rate-criteria-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
