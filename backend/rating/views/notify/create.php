<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Notify $model
 */
$this->title = Yii::t('app', 'Создать уведомление');
$this->params['breadcrumbs'][] = ['label' => 'Уведомления', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notify-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
