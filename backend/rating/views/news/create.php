<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\News $model
 */
$this->title = Yii::t('app', 'Добавить новость');
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
