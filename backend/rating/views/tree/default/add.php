<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>

<?php $this->title = 'Новый раздел'; ?>
<?= $this->render('_pathway', ['parents' => $parents, 'title' => $this->title] ); ?>

<?= $this->render('_form', [
    'model' => $model,
    'errors' => $errors,
    'parent_title' => $parent_title,
    'modules' => $modules,
    'sections' => $sections,
]) ?>
