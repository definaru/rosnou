<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\SiteSubject $model
 */
$this->title = Yii::t('app', 'Создать субъект федерации');
$this->params['breadcrumbs'][] = ['label' => 'Субъекты федерации', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-subject-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
