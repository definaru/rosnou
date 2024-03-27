<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\RateCriteria */

$this->title = 'Создать критерий';

$this->params['breadcrumbs'][] = ['label' => 'Период '. $criteriaType->period->title, 'url' => ['/rate/rate-period/criteria', 'id' => $criteriaType->period->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rate-criteria-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
