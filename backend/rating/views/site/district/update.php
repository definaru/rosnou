<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\SiteDistrict $model
 */

$this->title = Yii::t('app', 'Редактировать федеральный округ') . ': ' . $model->title;

$this->params['breadcrumbs'][] = ['label' => 'Федеральные округа', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['update', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $model->title;
?>
<div class="site-district-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <br>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
