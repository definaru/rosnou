<?php $this->breadcrumbs = ['Сайты'] ?>
<?php $this->title = 'Сайты' ?>

<?php if ($notify): ?>
    <div class="row">
        <div class="col-xs-12">
            <?= $notify->body ?>
        </div>
    </div>
<?php endif;?>
<div class="row">
    <div class="col-xs-12">
        <a href="<?=\yii\helpers\Url::toRoute('sites/sites/create')?>" class="btn btn-blue pull-right">
            Добавить сайт
        </a>
    </div>
</div>
<br>

<?php if($sites):?>

    <div class="row">
        <div class="col-xs-12">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название сайта
                            <div class="pull-right">
                                <i class="fa fa-caret-up fa-active"></i><i class="fa fa-caret-down"></i>
                            </div>
                        </th>
                        <th>Адрес сайта</th>
                        <th>Статус</th>
                        <th>Действия</th>
                        <th>Экспертиза</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach ($sites as $site): ?>

                        <tr>
                            <td><a href="<?=\yii\helpers\Url::toRoute(['sites/sites/edit', 'id' => $site['id']])?>"><?=$site['id']?></a></td>
                            <td>
                                <a href="<?=$site['domain']?>" target="_blank">
                                    <?=$site['title']?>
                                </a>
                            </td>
                            <td><?=$site['domain']?></td>
                            <td><?=$site['site_status']?></td>
                            <td>
                                <?=$this->render('index/actions', ['site' => $site, 'activePeriod' => $activePeriod])?>
                            </td>
                            <td>
                                <?php if($site['request_finished'] || $site['request_expert_draft']):?>
                                    номер <?=$site['request_queue_index']?> в очереди на экспертизу
                                <?php elseif($site['request_checked']):?>
                                    <?=$site['request_score']?> баллов из <?=$scoreBySiteTypes[$site['type_id']]?> возможных
                                <?php elseif($site['site_status_on_moderation']):?>
                                    номер <?=$site['queue_index']?> в очереди на проверку
                                <?php endif?>
                            </td>
                        </tr>

                    <?php endforeach ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php else:?>

    <p>Нет сайтов</p>

<?php endif?>
