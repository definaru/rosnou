<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\SiteSubjectSearch $searchModel
 */

$this->title = 'Субъекты федерации';
$this->params['breadcrumbs'][] = $this->title;
$dataProvider->setSort(['defaultOrder' => ['id'=>SORT_DESC] ]);

?>
<div class="site-subject-index">
    <!--
    <div class="page-header">
            <h1><?= Html::encode($this->title) ?></h1>
    </div>
    -->
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a('Create Site Subject', ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php /*Pjax::begin();*/ echo GridView::widget([
        'dataProvider' => $dataProvider,
        'pjax'=>true,
        'pjaxSettings'=>[
            'neverTimeout'=>true,
            'options' => [
                'id' => 'w2',
            ],
         ],
        /*'filterModel' => $searchModel,*/
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //[
            //    'class'=>'kartik\grid\CheckboxColumn',
            //    'headerOptions'=>['class'=>'kartik-sheet-style'],
            //],

            'id',
            'title',
            [
                'attribute' => 'district_id',
                'value' => function(\common\models\SiteSubject $data) {
                    return $data->district->title;
                },
            ],
            'map_code',
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                'view' => function($url, $model){
                    return null;
                },
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update','id' => $model->id], [
                                                    'title' => Yii::t('app', 'Edit'),
                                                  ]);}

                ],

                'contentOptions'=>['style'=>'min-width: 45px'],
            ],

        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,
        'toolbar' => false,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'Add'), ['create'], ['class' => 'btn btn-success']),
            'showFooter'=>false
        ],
    ]); /*Pjax::end();*/ ?>

</div>
