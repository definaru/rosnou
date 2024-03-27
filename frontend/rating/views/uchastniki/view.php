<?php
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Html;
?>
<?php $this->title = $this->pageHeader = $pageName?>
<div class="col-sm-9 col-xs-12">
    <div class="row">
        <div class="col-sm-4 col-xs-12 pull-right">
            <h3>
                <span class="pull-right small">
                    <a href="/uchastniki/" title="к списку участников">
                        к списку участников
                    </a>
                </span>
            </h3>
        </div>
        <div class="col-sm-8 col-xs-12 topic-title">
            <h2>
                <?= $Site->title; ?>
            </h2>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <table class="table">
                <tbody>
                    <tr>
                        <td class="col-xs-4">
                            Адрес сайта:
                        </td>
                        <td>
                            <a href="<?= $Site->domain; ?>" target="_blank">
                                <?= $Site->domain; ?>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Официальное название:
                        </td>
                        <td>
                            <?= $Site->title; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Федеральный округ:
                        </td>
                        <td>
                            <?= $Site->district->title; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Субъект федерации:
                        </td>
                        <td>
                            <?= $Site->subject->title; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Населенный пункт/АО:
                        </td>
                        <td>
                            <?= $Site->location; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <br>
        <div class="row">
            <div class="col-xs-12">
                <h3>
                    Результаты сайта
                </h3>
                <?= Html::beginForm(['uchastniki/view', 'id' => $Site->id], 'post',['name' => 'periods_form', 'id' => 'periods_form']) ?>
                  <div class="form-group form-group-full">
                      <?= Select2::widget([
                        'name' => 'PERIOD_ID',
                        'data' => $periods,
                        'value' => $periodID,
                        'options' => [
                            'placeholder' => 'Выберите период ...',
                        ],
                        'pluginEvents' => [
                          "change" => "function() { 
                            $('#periods_form').submit();
                          }",
                        ],
                      ]);
                  ?>
                </div>
                <?php Html::endForm() ?>
            </div>
        </div>
        <?php if ( sizeof($list['list']) > 0): ?>
            <?php if ($Period->finished_flag == 1 ): ?>
                <div class="row">
                    <div class="col-xs-12">
                        <br>
                            <div class="row">
                                <div class="col-md-11">
                                    <h3>
                                        Критерий
                                    </h3>
                                </div>
                                <div class="col-md-1">
                                    <h3>
                                        Балл
                                    </h3>
                                </div>
                            </div>
                            <?php foreach ($list['list'] as $CriteriaTypeID => $CriteriaType): ?>
                            <div class="panel-group" role="tablist">
                                <div class="panel panel-default">
                                    <div class="panel-heading" id="panelHeader<?= $CriteriaTypeID ?>" role="tab" style="cursor: pointer" >
                                        <span aria-controls="panel<?= $CriteriaTypeID ?>" aria-expanded="true" class="" data-toggle="collapse" href="#panel<?= $CriteriaTypeID ?>" role="button">
                                              <h4 class="panel-title">
                                                <?= $CriteriaType['title'] ?>
                                                <span class="label label-info pull-right">
                                                    <?= $CriteriaType['count'] ?>
                                                </span>
                                            </h4>
                                        </span>
                                    </div>
                                    <div aria-expanded="true" aria-labelledby="panelHeader<?= $CriteriaTypeID ?>" class="panel-collapse collapse out" id="panel<?= $CriteriaTypeID ?>" role="tabpanel" style="">
                                        <ul class="list-group">
                                           <?php foreach ($CriteriaType['list'] as $Criteria): ?> 
                                              <li class="list-group-item">
                                                  <div class="row">
                                                      <div class="col-md-11">
                                                          <?= $Criteria['title'] ?>
                                                      </div>
                                                      <div class="col-md-1 text-right">
                                                          <span class="label <?= $Criteria['score'] >0 ? 'label-info' : 'label-danger'?>">
                                                              <?= $Criteria['score'] ?>
                                                          </span>
                                                      </div>
                                                  </div>
                                              </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                          <?php endforeach; ?>
                            <div class="row">
                                <div class="col-md-11">
                                    <h3>
                                        Итоговый балл
                                    </h3>
                                </div>
                                <div class="col-md-1">
                                    <h3>
                                        <?= $list['count_all']; ?>
                                    </h3>
                                </div>
                            </div>
                        </br>
                    </div>
                </div>
          <?php else: ?>
            Сайт является участником рейтинга в выбранном периоде. Результаты участия будут опубликованы в соответствии с регламентом и сроками проведения рейтинга
          <?php endif; ?>  
      <?php else: ?>
        Сайт не участвовал в рейтинге в выбранном периоде
      <?php endif; ?>


      <?php /*

      <h1>requests</h1>
      <pre><?php var_dump($Site->requests);?></pre>
      <h1>Type</h1>
      <pre><?php var_dump($Site->type);?></pre>      
      <h1>subject</h1>
      <pre><?php var_dump($Site->subject);?></pre>      
      */ ?>

    </br>
</div>
<?= $this->render('right_menu',['Site' => $Site, 'request_finished' => $request_finished, 'ScoreData' => $ScoreData]); ?>