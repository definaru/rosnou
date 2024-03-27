<?php use yii\helpers\Url;?>
<?php $this->title = $section ? $section->title : 'Корневые разделы'?>

<?=$this->render('_pathway', ['parents' => $pathway, 'title' => $this->title] )?>

<table class="table">
    <?php foreach ($tree as $row): ?>
        <tr>
            <td><?=str_repeat('&nbsp;&nbsp;&bullet;&nbsp;&nbsp;', $row['level'])?><?= $row['title'] ?></td>
            <td><a href="<?=Url::to($row['route'])?>/" target="_blank"><?=$row['route']?></a></td>
            <td style="white-space: nowrap">
                <a title="редактировать" href="<?= Url::to(['tree/default/detail', 'id' => $row['id']]); ?>">
                    <i class="glyphicon glyphicon-pencil"></i>
                </a>

            </td>
        </tr>
    <?php endforeach?>
</table>

<a style="margin:20px 0" href="<?= Url::to(['tree/default/add'])?>"
   class="btn btn-primary pull-right">Добавить запись
</a>
