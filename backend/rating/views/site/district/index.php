<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\SiteDistrictSearch $searchModel
 */

$this->title = 'Федеральные округа';
$this->params['breadcrumbs'][] = $this->title;

$dataProvider->setSort(['defaultOrder' => ['id'=>SORT_DESC] ]);

?>
<div class="site-district-index">

    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php echo GridView::widget([

        'dataProvider' => $dataProvider,
        
        'pjax'=>false,
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
                //'class'=>'kartik\grid\CheckboxColumn',
                //'headerOptions'=>['class'=>'kartik-sheet-style'],
            //],

            'id',
            'title',

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

        //'export' => false,
        'toolbar' => false,
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,

        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'Add'), ['create'], ['class' => 'btn btn-success']),
            'after'=>null,
            'showFooter'=>false
        ],

    ]); ?>

</div>