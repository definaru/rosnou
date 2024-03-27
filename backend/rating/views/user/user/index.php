<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\UserSearch $searchModel
 */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
$dataProvider->setSort(['defaultOrder' => ['id'=>SORT_DESC] ]);

?>
<div class="user-index">

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p></p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pjax'=>true,
        'pjaxSettings'=>[
            'neverTimeout'=>true,
            'options' => [
                'id' => 'w2',
            ],
         ],
        'columns' => [

            //[
            //    'class'=>'kartik\grid\CheckboxColumn',
            //    'headerOptions'=>['class'=>'kartik-sheet-style'],
            //],

            'id',
            'email:email',
            

            [
                'header' => 'Данные',
                'format' => 'raw',
                'value' => function($row){

                    $list[] = "<b>ФИО:</b> {$row->lastname} {$row->firstname} {$row->middlename}";
                    $list[] = "<b>Организация:</b> {$row->orgname}";
                    $list[] = "<b>Должность:</b> {$row->position}";

                    return join($list, '<br>');
                }
            ],


            [
                'header' => 'Роль',
                'value' => function(\common\models\User $user) {
                    $roles = [];

                    if($user->is_moderator) {
                        $roles[] = 'Модератор';
                    }

                    if($user->is_admin) {
                        $roles[] = 'Супервизор';
                    }

                    if($user->is_expert) {
                        $roles[] = 'Эксперт';
                    }

                    if(!$roles) {
                        $roles[] = 'Пользователь';
                    }

                    return implode(', ', $roles);
                },
            ],

            'email_verified:boolean',

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
        'toolbar' => false,
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'Add'), ['create'], ['class' => 'btn btn-success']),
            'showFooter'=>false
        ],
    ]); ?>

</div>
