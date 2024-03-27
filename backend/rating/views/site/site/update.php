<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Site $model
 */

$this->title = Yii::t('app', 'Редактирование сайта') . ': ' . $model->title;

$this->params['breadcrumbs'][] = ['label' => 'Сайты', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['update', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $model->title;
?>
<div class="site-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <br>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
