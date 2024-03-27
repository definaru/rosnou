<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\RatePeriodSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Периоды';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rate-period-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать период', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'format' => 'raw',
                'label'  => 'Название',
                'contentOptions' => ['style' => 'width:40%;'],
                'value' => function($data) {
                    return $data->active_flag ? '<strong>'.$data->title.'</strong>' : $data->title;
                }
            ],
            [
                'format' => 'raw',
                'label'  => 'Активность',
                'value' => function($data) {
                    return $data->active_flag ? "<strong>Да</strong>" : 'Нет';
                }
            ],
            [
                'format' => 'raw',
                'label'  => 'Состояние',
                'value' => function($data) {
                    $result = 'первичное&nbsp;самообследование: ';
                    $result .= $data->request1_accept_flag ? '<strong>Да</strong>' : 'нет';

                    $result .= '<br>вторичное&nbsp;самообследование: ';
                    $result .= $data->request2_accept_flag ? '<strong>Да</strong>' : 'нет';

                    $result .= '<br>регистрация&nbsp;участников: ';
                    $result .= $data->register_accept_flag ? '<strong>Да</strong>' : 'нет';

                    $result .= '<br>период&nbsp;завершен: ';
                    $result .= $data->finished_flag ? '<strong>Да</strong>' : 'нет';

                    return $result;
                }
            ],   
            'list_order',     
            [
                'format' => 'raw',
                'value' => function($data) {
                    return Html::a('критерии', ['rate/rate-period/criteria', 'id' => $data->id]);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{updateButton}',
                'buttons' => [
                    'updateButton' => function($url, $model, $key) {     // render your custom button
                            return Html::a('настройки', ['update', 'id'=>$model->id]);
                    },
                ]
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{copyButton}',
                'buttons' => [
                    'copyButton' => function($url, $model, $key) {     // render your custom button
                            return Html::a('копировать', ['copy', 'id'=>$model->id],[
                                        'data' => [
                                            'confirm' => 'Создать новый период с такими же критериями как в текущем периоде?',
                                            'method' => 'post',
                                        ],
                            ]);
                    }
                ]
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
            ],
        ],
    ]); ?>
</div>
