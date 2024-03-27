<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Site $model
 */
$this->title = Yii::t('app', 'Create Site');
$this->params['breadcrumbs'][] = ['label' => 'Sites', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
