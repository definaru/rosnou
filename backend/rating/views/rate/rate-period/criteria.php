<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\RateCriteriaTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Критерии';
$this->params['breadcrumbs'][] = ['label' => 'Периоды', 'url' => ['/rate/rate-period']];
$this->params['breadcrumbs'][] = 'Критерии';
?>
<tr class="rate-criteria-type-index">

    <h1><?= Html::encode('Критерии для периода "'. $period->title . '"') ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php foreach($siteTypes as $siteType):?>

        <?= Html::a('Добавить группу', ['/rate/rate-criteria-type/create', 'periodId' => $period->id, 'siteType' => $siteType->id], ['class' => 'btn btn-success btn-xs pull-right']) ?>

        <h3>
            <?=$siteType->title?>
        </h3>

        <?php if(isset($criteriaTypes[$siteType->id])):?>

            <table class="table table-bordered">

            <?php foreach($criteriaTypes[$siteType->id] as $criteriaType):?>

                <tr>
                    <td><?=$criteriaType->title?></td>
                    <td width="100">
                       <?=$criteriaType->hidden_flag ? '<span class="badge badge-warning">скрытый</span>' : ''?>   
                    </td>
                    <td width="100">
                        <?= Html::a('Добавить критерий', ['/rate/rate-criteria/create', 'type' => $criteriaType->id], ['class' => 'btn btn-success btn-xs']) ?>
                    </td>
                    <td width="100">
                        <?=Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['/rate/rate-criteria-type/update', 'id' => $criteriaType->id])?>
                        <?=Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/rate/rate-criteria-type/delete', 'id' => $criteriaType->id], [
                            'data-confirm' => 'Удалить?',
                            'data-method' => 'post',
                        ])?>
                    </td>
                </tr>

                <?php if(isset($criterias[$criteriaType->id])):?>

                    <tr>
                        <td colspan="3" style="padding: 0">
                            <table class="table table-bordered" style="margin-bottom: 0;">
                                <?php foreach($criterias[$criteriaType->id] as $criteria):?>
                                    <tr>
                                        <td style="padding-left: 30px;"><?=$criteria->title?></td>
                                        <td width="180" style="text-align: left;">
                                            <?php 
                                                $value = "баллы: {$criteria->score}";
                                                if ($criteria->field_type == 1) {
                                                    $value .= "<br>тип: да/нет";   
                                                } elseif($criteria->field_type == 2) {
                                                    $value .= "<br>тип: список";
                                                }
                                                if ($criteria->field_name) {
                                                    $value .= "<br>список: $criteria->field_name";    
                                                }
                                                if ($criteria->function) {
                                                    $value .= "<br>функция: $criteria->function";    
                                                }                                             
                                                echo $value;
                                            ?>
                                            </td>
                                        <td width="80">
                                            <?=Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['/rate/rate-criteria/update', 'id' => $criteria->id])?>
                                            <?=Html::a('<span class="glyphicon glyphicon-trash"></span>', ['/rate/rate-criteria/delete', 'id' => $criteria->id], [
                                                'data-confirm' => 'Удалить?',
                                                'data-method' => 'post',
                                            ])?>
                                        </td>
                                    </tr>
                                <?php endforeach?>
                            </table>

                        </td>
                    </tr>



                <?php endif?>

            <?php endforeach?>

            </table>

        <?php endif?>

    <?php endforeach?>
</div>
