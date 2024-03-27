<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\RatePeriod */

$this->title = 'Создание периода';
$this->params['breadcrumbs'][] = ['label' => 'Rate Periods', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rate-period-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
