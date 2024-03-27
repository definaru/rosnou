<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Banner $model
 */

$this->title = Yii::t('app', 'Обновить баннер') . ': ' . $model->title;

$this->params['breadcrumbs'][] = ['label' => 'Баннеры', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['update', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $model->title;
?>
<div class="banner-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <br>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
