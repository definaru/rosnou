<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\SiteDistrict $model
 */
$this->title = Yii::t('app', 'Создать федеральный округ');
$this->params['breadcrumbs'][] = ['label' => 'Федеральные округа', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-district-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
