<?php $this->title = $this->pageHeader = $pageName ?>

<?php foreach($siteTypes as $siteType):?>
    <div class="panel-group">
        <div class="panel panel-default">
            <div id="1cat_header" class="panel-heading">
                <h4 class="panel-title"><a href="#site-type-<?=$siteType->id?>" data-toggle="collapse"><?=$siteType->title?></a></h4>
            </div>
            <div id="site-type-<?=$siteType->id?>" class="panel-collapse collapse">
                <div class="table-responsive">
                    <table class="table">
                        <tbody>

                            <?php if(isset($criteriaTypes[$siteType->id])):?>
                                <?php foreach($criteriaTypes[$siteType->id] as $criteriaType):?>

                                    <tr>
                                        <td>&nbsp;</td>
                                        <th colspan="2" style="text-transform: uppercase;"><?=$criteriaType->title?></th>
                                    </tr>

                                    <?php $totalScore = 0; if(isset($criterias[$criteriaType->id])):?>

                                        <?php foreach($criterias[$criteriaType->id] as $index => $criteria):?>

                                            <tr>
                                                <td align="right"><?=$index + 1?></td>
                                                <td><?=$criteria->title?></td>
                                                <td><?=$criteria->score?></td>
                                            </tr>

                                        <?php $totalScore+= $criteria->score; endforeach?>

                                    <?php endif?>

                                    <tr>
                                        <td>&nbsp;</td>
                                        <td><strong>Итого: <?=$criteriaType->title?></strong></td>
                                        <td><?=$totalScore?></td>
                                    </tr>

                                <?php endforeach?>
                            <?php endif?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php endforeach?>