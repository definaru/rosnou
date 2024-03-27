<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;


/**
 * @var yii\web\View $this
 * @var yii\gii\generators\crud\Generator $generator
 */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n"; ?>

use yii\helpers\Url;
use yii\helpers\Html;
use <?= $generator->indexWidgetType === 'grid' ? "kartik\\grid\\GridView" : "yii\\widgets\\ListView" ?>;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
<?= !empty($generator->searchModelClass) ? " * @var " . ltrim($generator->searchModelClass, '\\') . " \$searchModel\n" : '' ?>
 */

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$this->params['breadcrumbs'][] = $this->title;
$dataProvider->setSort(['defaultOrder' => ['id'=>SORT_DESC] ]);

?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">
    <!--
    <div class="page-header">
            <h1><?= "<?= " ?>Html::encode($this->title) ?></h1>
    </div>
    -->
<?php if(!empty($generator->searchModelClass)): ?>
<?= "    <?php " . ($generator->indexWidgetType === 'grid' ? "" : "") ?>echo $this->render('_search', ['model' => $searchModel]); ?>
<?php endif; ?>

    <p>
        <?= "<?php /* echo " ?>Html::a(<?= $generator->generateString('Create {modelClass}', ['modelClass' => Inflector::camel2words(StringHelper::basename($generator->modelClass))]) ?>, ['create'], ['class' => 'btn btn-success'])<?= "*/ " ?> ?>
    </p>

<?php if ($generator->indexWidgetType === 'grid'): ?>
    <?= "<?php /*Pjax::begin();*/ echo " ?>GridView::widget([
        'dataProvider' => $dataProvider,
        'pjax'=>true,
        'pjaxSettings'=>[
            'neverTimeout'=>true,
            'options' => [
                'id' => 'w2',
            ],
         ],
        <?= !empty($generator->searchModelClass) ? "/*'filterModel' => \$searchModel,*/\n        'columns' => [\n" : "'columns' => [\n"; ?>
            //['class' => 'yii\grid\SerialColumn'],

            [
                'class'=>'kartik\grid\CheckboxColumn',
                'headerOptions'=>['class'=>'kartik-sheet-style'],
            ],

<?php
$count = 0;
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        if (++$count < 6) {
            echo "            '" . $name . "',\n";
        } else {
            echo "            // '" . $name . "',\n";
        }
    }
} else {
    foreach ($tableSchema->columns as $column) {

        $format = $generator->generateColumnFormat($column);

        if( $column->name == 'body' || $column->type == 'text' ){
            continue;
        }elseif( substr($column->name, -5, 5) === '_flag'){

            $columnDisplay = "            [
                'attribute'=>'$column->name',
                'class'=>'kartik\grid\BooleanColumn',
                'vAlign'=>'middle',
            ],";

        }elseif( substr($column->name, -6, 6) === '_index'){

            $columnDisplay = '            [
                \'attribute\' => \''.$column->name.'\',
                \'format\'=>\'raw\',
                \'value\'=>function ($model, $key, $index, $widget) {

                    return $model->get' . Inflector::id2camel($column->name, '_') . 'List(intval($model->'. $column->name .'));
                },
            ],';

        }elseif( substr($column->name, -3, 3) === '_id'){

            $columnName = substr($column->name, 0, -3);
            $objectName = Inflector::id2camel($columnName, '_');

            $foreignClass = "\common\models\\". ucfirst($objectName);


            if( class_exists($foreignClass) ){

            $foreignModel = new $foreignClass();
            $foreignAttrs = $foreignModel->attributes();

            $nameAttr = $column->name == 'user_id' ? 'username' : 'id';
            $nameAttr = in_array('title', $foreignAttrs) ? 'title' : $nameAttr;
            $nameAttr = in_array('name', $foreignAttrs) ? 'name' : $nameAttr;

            $columnDisplay = '            [
                \'attribute\' => \''.$column->name.'\',
                \'format\'=>\'raw\',
                \'value\'=>function ($model, $key, $index, $widget) {

                    if( !method_exists($model,"get'.$objectName.'")){
                        return null;
                    }

                    $url = Url::to([\''.$objectName.'/update\',\'id\' =>$model->'.$columnName.'->id]);

                    return Html::a($model->'.$columnName.'->' . $nameAttr .', \'#\', [
                        \'title\'=>\'View detail\',
                        \'onclick\' => \'window.location = "\'.$url.\'"\',
                    ]);
                },
            ],';

            } else {

            $columnDisplay = "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',";

            }

        }elseif( substr($column->name, -6, 6) === '_image'){

            $objectName = substr($column->name, 0, -3);
            $nameAttr = $column->name == 'user_id' ? 'username' : 'title';

            $columnDisplay = "[
                'attribute'=>'{$column->name}',
                'format' => 'html',
                'value' => function(\$model) {
                    //return Html::img( \$model->getUploadUrl('".$column->name."'), ['width'=>'100']);
                    if (!file_exists('.'.\$model->".$column->name.")){
                      return Html::img( \$model->".$column->name.", ['width'=>'100']);
                    }
                },
            ],";

        }elseif(substr($column->name, -5, 5) === '_file'){

          continue;

          /*$columnDisplay = "[
            'attribute'=>'{$column->name}',
            'format' => 'html',
            'value' => function(\$model) {
                return substr(\$model->".$column->name.",strripos(\$model->".$column->name.",'/')+1);

            },
          ],";*/

        }elseif( substr($column->name,-5,5) == '_date' || $column->type === 'date'){

            $columnDisplay = "            ['attribute'=>'$column->name','format'=>['date']],";

        }elseif($column->type === 'time' || substr($column->name,-5,5) == '_time'){

            $columnDisplay = "            ['attribute'=>'$column->name','format'=>['time']],";

        }elseif($column->type === 'datetime' || $column->type === 'timestamp' || substr($column->name,-9,9) == '_datetime'){

            $columnDisplay = "            ['attribute'=>'$column->name','format'=>['datetime']],";

        }else{
            $columnDisplay = "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',";
        }
        if (++$count < 20) {
            echo $columnDisplay ."\n";
        } else {
            echo "//" . $columnDisplay . " \n";
        }
    }
}
?>

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                'view' => function($url, $model){
                    return null;
                },
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update',<?= $urlParams ?>], [
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
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'Add'), ['create'], ['class' => 'btn btn-success']),

            'after'=>Html::button('<i class="glyphicon glyphicon-remove"></i> ' .Yii::t('app', 'Multydelete'),['class' => 'btn btn-danger kv-batch-delete', 'onclick' => '(function ( $event ) { if (confirm("Выбранные записи будут удалены без возможности восстановления. Удалить выбранные записи?")) {

        var $grid = $("#w2");
        var selectedList = $grid.yiiGridView("getSelectedRows");

        if( selectedList.length == 0 ){
            return false;
        }

        $.post("multydelete", { pk: selectedList }, function (data) {
            $grid.yiiGridView("applyFilter");
        });

      }})(); return false']),


            //'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> '. Yii::t('app', 'Reset list'), ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); /*Pjax::end();*/ ?>
<?php else: ?>
    <?= "<?= " ?>ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['update', <?= $urlParams ?>]);
        },
    ]) ?>
<?php endif; ?>

</div>
