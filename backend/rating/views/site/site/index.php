<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\SiteSearch $searchModel
 */

$this->title = 'Сайты';
$this->params['breadcrumbs'][] = $this->title;
$dataProvider->setSort(['defaultOrder' => ['id'=>SORT_DESC] ]);

?>
<div class="site-index">

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'pjax'=>true,
        'pjaxSettings'=>[
            'neverTimeout'=>true,
            'options' => [
                'id' => 'w2',
            ],
         ],
        'columns' => [

            'id',
            
            'title',

            [
                'attribute' => 'domain',
                'format' => 'raw',
                'value' => function($row){
                    return Html::a($row->domain, $row->domain, ['target' => '_blank']);
                }

            ],
            
            [
                'header' => 'Данные',
                'format' => 'raw',
                'value' => function($row){

                    $list[] = "<b>Тип:</b> " . ($row->type->title ?? '');
                    $list[] = "<b>Статус:</b> " . ($row->statusName ?? '');
                    $list[] = "<b>Пользователь:</b> " . ($row->user->fio ?? '');
                    $list[] = "<b>Субъект:</b> " . ($row->subject->title ?? '');
                    $list[] = "<b>Округ:</b> " . ($row->district->title ?? '');

                    return join($list, '<br>');
                }
            ],

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
            'showFooter'=>false
        ],
    ]); ?>

</div>
