<?php
use yii\helpers\Html;
$this->title = $this->pageHeader = $pageName;
?>
<?php /*
<pre><?php var_dump($list);?></pre>
<pre><?php var_dump($test);?></pre>
<pre><?php var_dump($scoreRules);?></pre>
*/ ?>

<div class="row">
    <div class="col-md-12">
        <div class="table-border">
            <table class="table table-hover tablesorter" id="tablesorter">
                <thead>
                    <tr>
                        <th class="header">
                            Балл
                        </th>
                        <th class="header">
                            Участник
                        </th>
                        <th class="header">
                            Населенный пункт / АО
                        </th>
                        <th class="header">
                            Субъект федерации
                        </th>
                        <th class="header">
                            Федеральный округ
                        </th>
                        <th class="header">
                            Сайт
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($list as $Site): ?>
                    <tr class=" <?= $scoreRules[$Site['request_id']]['color_class'] ?> ">
                        <td>
                            <?= $Site['score'] ?>
                        </td>
                        <td>
                            <?= Html::a($Site['title'], ['uchastniki/view', 'id' => $Site['id']], [
                                'data'=>[
                                    'method' => 'post',
                                    'params'=>['PERIOD_ID'=>$period_id],                                ]
                            ]) ?>
                        </td>
                        <td>
                            <?= $Site['location'] ?>
                        </td>
                        <td>
                            <?= $Site['sbjTitle'] ?>
                        </td>
                        <td>
                            <?= $Site['distTitle'] ?>
                        </td>
                        <td>
                            <a href="<?= $Site['domain'] ?>" rel="nofollow" target="_blank">
                                <em class="fa fa-globe">
                                </em>
                            </a>
                            <div class="ratingresult-siteurl">
                                <?= $Site['domain'] ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>