<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>

<?php $this->title = $model->title; ?>
<?= $this->render('_pathway', ['parents' => $parents, 'title' => $model->title] ); ?>

<?= $this->render('_form', [
    'model' => $model,
    'errors' => $errors,
    'parent_title' => $parent_title,
    'modules' => $modules,
    'sections' => $sections,
]) ?>

<?php /* if($model['trash_flag'] == 0): ?>
    <a class="btn btn-default pull-right btn-danger" data-href="<?= Url::to(['tree/default/delete', 'id' => $model['id']]); ?>" data-toggle="confirmation">Скрыть</a>
<?php else: ?>
    <a class="btn btn-default pull-right btn-primary" data-href="<?= Url::to(['tree/default/enable', 'id' => $model['id']]); ?>" data-toggle="confirmation">Показать</a>
<?php endif; */?>

<p style="text-align: right">Функционал скрыть/показать влияет на текущий раздел и все дочерние подразделы,<br> которые относятся к текущему.</p>
