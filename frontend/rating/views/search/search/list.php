<?php
use yii\bootstrap\ActiveForm;
?>

<?php $this->title = $this->pageHeader = $pageName?>

<div class="row">
  <div class="col-md-12">
  <?php $form = ActiveForm::begin([
    'id'      => 'searh-form',
    'action'  => '/search/search_do/',
    'method' => 'get',
    'options' => ['class' => 'form-horizontal'],
  ])?>
      <div class="col-md-10">
        <?=$form->field($model, 'query')->label(false);?>
        <span class="help-block">По вашему запросу "<?= $model->query ?>" найдено страниц: <?= $list['pages']->totalCount ?>
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-blue">Найти</button>
      </div>
  <?php ActiveForm::end()?>
  </div>
</div>

<div class="row">

  <?php foreach ($list['list'] as $item): ?>
  <div class="col-md-12">
    <p class="uppercase"><a href="<?= $item['path'] ?>"><?= $item['title'] ?></a></p>
    <p>
      <?= $item['content'] ?> 
    </p>
    <br/>
    <br/>
  </div>
  <?php endforeach; ?>
    <div>
    <!-- PAGES -->
    <nav class="pages">
        <?= yii\widgets\LinkPager::widget(['pagination' => $list['pages']]); ?>
    </nav>
  </div>
</div>