<?php 
  use yii\widgets\Pjax;

  $this->title = $this->pageHeader = $pageName;
  //$this->blocks['pageHeaderContent'] = $form;
?>
<div class="row">
  <div class="col-md-12">     
      <?= $section->body ?>
  </div>
</div>
<?php Pjax::begin(); ?>
<div class="row">
  <div class="col-md-12">
    <?= $this->render('_form',[
            'siteTypes_list' => $siteTypes_list,
            'siteDistrict_list' => $siteDistrict_list,
            'siteSubject_list' => $siteSubject_list,
            'model' => $model,
        ]);?>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="table-responsive">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>
              <div>Название сайта
                <div class="arrows">
                  <a href="<?= yii\helpers\Url::current(['order'=> 'title']); ?>" title="Сортировать по возрастанию">
                    <i class="fa fa-caret-up <?= $order =='title' ? 'active_arrow_sort':'' ?>"></i>
                  </a>
                  <a href="<?= yii\helpers\Url::current(['order'=> 'title desc']); ?>" title="Сортировать по убыванию">
                    <i class="fa fa-caret-down  <?= $order =='title desc' ? 'active_arrow_sort':'' ?>"></i>
                  </a>
                </div>
              </div>
            </th>
            <th>
              <div>Населенный пункт / АО
                <div class="arrows">
                  <a href="<?= yii\helpers\Url::current(['order'=> 'location']); ?>" title="Сортировать по возрастанию">
                    <i class="fa fa-caret-up  <?= $order =='location' ? 'active_arrow_sort':'' ?>"></i>
                  </a>
                  <a href="<?= yii\helpers\Url::current(['order'=> 'location desc']); ?>" title="Сортировать по убыванию">
                    <i class="fa fa-caret-down <?= $order =='location desc' ? 'active_arrow_sort':'' ?>"></i>
                  </a>
                </div>
              </div>
            </th>
            <th>
              <div>Субъект федерации
                <div class="arrows">
                  <a href="<?= yii\helpers\Url::current(['order'=> 'sbj.title']); ?>">
                    <i class="fa fa-caret-up <?= $order =='sbj.title' ? 'active_arrow_sort':'' ?>">
                    </i>
                  </a>
                  <a href="<?= yii\helpers\Url::current(['order'=> 'sbj.title desc']); ?>"><i class="fa fa-caret-down <?= $order =='sbj.title desc' ? 'active_arrow_sort':'' ?>"></i>
                  </a>
                </div>
              </div>
            </th>
            <th>
              <div>Федеральный округ
                <div class="arrows">
                  <a href="<?= yii\helpers\Url::current(['order'=> 'd.title']); ?>">
                    <i class="fa fa-caret-up <?= $order =='d.title' ? 'active_arrow_sort':'' ?>"></i>
                  </a>
                  <a href="<?= yii\helpers\Url::current(['order'=> 'd.title desc']); ?>">
                    <i class="fa fa-caret-down <?= $order =='d.title desc' ? 'active_arrow_sort':'' ?>"></i>
                  </a>
                </div>
              </div>
            </th>
            <th>
              <div>Категория
                <div class="arrows">
                  <a href="<?= yii\helpers\Url::current(['order'=> 't.title']); ?>">
                    <i class="fa fa-caret-up <?= $order =='t.title' ? 'active_arrow_sort':'' ?>"></i>
                  </a>
                  <a href="<?= yii\helpers\Url::current(['order'=> 't.title desc']); ?>">
                    <i class="fa fa-caret-down <?= $order =='t.title desc' ? 'active_arrow_sort':'' ?>"></i>
                  </a>
                </div>
              </div>
            </th>
            <th>Сайт</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($list['list'] as $Site): ?>
          <tr>
            <td>
              <a href="/uchastniki/<?= $Site['id']?>/"><?= $Site['title']?></a>
            </td>
            <td>
              <?= $Site['location']?>
            </td>
            <td>
              <?= $Site['subject']['title'] ?>
            </td>
            <td>
              <?= $Site['district']['title'] ?>
            </td>
            <td>
              <?= $Site['type']['title'] ?>
            </td>
            <td>
              <a href="<?= $Site['domain']?>" target="_blank" title=""><i class="fa fa-globe"></i></a>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div>
    <!-- PAGES -->
    <nav class="pages">
        <?= yii\widgets\LinkPager::widget(['pagination' => $list['pages']]); ?>
    </nav>
  </div>
  </div>
</div>
<?php Pjax::end(); ?>