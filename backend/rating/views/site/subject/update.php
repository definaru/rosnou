<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\SiteSubject $model
 */

$this->title = Yii::t('app', 'Редактировать субъект федерации') . ': ' . $model->title;

$this->params['breadcrumbs'][] = ['label' => 'Субъект федерации', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['update', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $model->title;
?>
<div class="site-subject-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <br>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
