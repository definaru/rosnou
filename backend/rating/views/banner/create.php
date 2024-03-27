<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Banner $model
 */
$this->title = Yii::t('app', 'Создать баннер');
$this->params['breadcrumbs'][] = ['label' => 'Баннеры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banner-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
