<?php use yii\grid\GridView; ?>

<div class="row">
    <div class="col-xs-12">
        <div class="table-responsive">
            <?=GridView::widget([
                'layout' => "{items}",
                'tableOptions' => ['class' => 'table table-striped table-hover'],
                'dataProvider' => $dataProvider,
                'columns' => [
                    'queue_index',
                    ['attribute' => 'created_timestamp',
                        'format'=>'datetime',

                    ],
                    [
                        'header' => 'Статус',
                        'value' => function (\common\models\Site $data) {
                            return $data->getStatusIndexList($data->status_index);
                        }
                    ],
                    [
                        'attribute' => 'title',
                        'header' => 'Название сайта',
                        'format' => 'raw',
                        'value' => function ($data) {
                            return \yii\helpers\Html::a($data->title, \yii\helpers\Url::toRoute(['sites/sites/moderate-edit', 'id' => $data->id]));
                        }
                    ],
                    [
                        'attribute' => 'domain',
                        'header' => 'Адрес сайта',
                        'format' => 'raw',
                        'value' => function ($data) {
                            return \yii\helpers\Html::a($data->domain, $data->domain, ['target' => '_blank']);
                        }
                    ],
                    [
                        'attribute' => 'site.type_id',
                        'header' => 'Категория',
                        'value' => function ($data) use ($siteTypes) {
                            return $siteTypes[$data->type_id];
                        }
                    ],
                    //[
                    //    'attribute' => 'comment',
                    //    'header' => 'Комментарий',
                    //    'value' => function ($data) {
                    //        return $data->moderator_comment ?: '';
                    //    }
                    //],
                ],
            ])?>
        </div>
    </div>
</div>