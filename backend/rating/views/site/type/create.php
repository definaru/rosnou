<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\SiteType $model
 */
$this->title = Yii::t('app', 'Добавить тип сайта');
$this->params['breadcrumbs'][] = ['label' => 'Типы сайтов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-type-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
