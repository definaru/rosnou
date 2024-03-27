<?php $this->title = 'Заявки' ?>

<div class="row">
    <div class="col-xs-12">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>Очередь</th>
                    <th>Статус</th>
                    <th>Дата, время самообследования</th>
                    <th>Название сайта</th>
                    <th>Адрес сайта</th>
                    <th>Категория</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th colspan="6">Заявки закрепленные за мной</th>
                </tr>

                <?php foreach($expertItems as $item):?>
                    <tr>
                        <td><?=$item->queue_index?></td>
                        <td><?=$item->statusLabel()?></td>
                        <td><?=$item->created_datetime?></td>
                        <td><a href="<?=\yii\helpers\Url::toRoute(['users/examination/expert-check', 'id' => $item->id])?>"><?=$item->site->title?></a></td>
                        <td><a href="<?=$item->site->domain?>" target="_blank"><?=$item->site->domain?></a></td>
                        <td><?=$item->site->type->title?></td>
                    </tr>
                <?php endforeach?>

                <tr>
                    <th colspan="6">Свободные заявки</th>
                </tr>

                <?php foreach($freeItems as $item):?>
                    <tr>
                        <td><?=$item->queue_index?></td>
                        <td><?=$item->statusLabel()?></td>
                        <td><?=$item->created_datetime?></td>
                        <td><a href="<?=\yii\helpers\Url::toRoute(['users/examination/expert-check', 'id' => $item->id])?>"><?=$item->site->title?></a></td>
                        <td><a href="<?=$item->site->domain?>" target="_blank"><?=$item->site->domain?></a></td>
                        <td><?=$item->site->type->title?></td>
                    </tr>
                <?php endforeach?>

                <tr>
                    <th colspan="6">Заявки прошедшие мою экспертизу</th>
                </tr>

                <?php foreach($expertFinishedItems as $item):?>
                    <tr>
                        <td></td>
                        <td><?=$item->statusLabel()?></td>
                        <td><?=$item->created_datetime?></td>
                        <td><?=$item->site->title?></td>
                        <td><a href="<?=$item->site->domain?>" target="_blank"><?=$item->site->domain?></a></td>
                        <td><?=$item->site->type->title?></td>
                    </tr>
                <?php endforeach?>

                </tbody>
            </table>
        </div>
    </div>
</div>