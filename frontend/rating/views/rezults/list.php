<?php $this->title = $this->pageHeader = $pageName?>
<div class="row">
    <div class="col-md-12">
        <?php /*
        <pre><?php var_dump($list);?></pre>
        <ul>
        <?php foreach ($list as $Period): ?>
            <li><?= $Period->title ?> - <?= $Period->slug ?></li>
        <?php endforeach; ?>
        </ul>

        <pre><?php var_dump($siteTypes_list);?></pre>        
        */ ?>


        <?php foreach ($list as $Period): ?>
        <div class="panel-group">
            <div class="panel panel-default">
                <div class="panel-heading" id="<?= $Period->id ?>pan_header">
                    <h4 class="panel-title">
                        <a aria-expanded="true" class="" data-toggle="collapse" href="#<?= $Period->id ?>pan">
                            <?= $Period->title ?>
                        </a>
                    </h4>
                </div>
                <div aria-expanded="true" class="panel-collapse collapse out" id="<?= $Period->id ?>pan" style="padding: 10px;">
                    <?php foreach ($siteTypes_list as $typeSlug => $typeTitle): ?>
                    <p>
                        <a href="/rezults/<?= $Period->slug ?>/<?= $typeSlug ?>/">
                            <?= $typeTitle ?>
                        </a>
                    </p>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <div class="panel-group">
            <div class="panel panel-default">
                <div class="panel-heading" id="2pan_header">
                    <h4 class="panel-title">
                        <a aria-expanded="false" class="collapsed" data-toggle="collapse" href="#2pan">
                            Архив
                        </a>
                    </h4>
                </div>
                <div aria-expanded="false" class="panel-collapse collapse" id="2pan" style="padding: 10px; height: 20px;">
                    <p>
                        <a href="http://rating.rosnou.ru/?q=rating3-5" title="" target=_blank>
                            Рейтинг 3.5 (Зима 2015)
                        </a>
                    </p>
                    <p>
                        <a href="http://rating.rosnou.ru/?q=summer2014" title="" target=_blank>
                            Рейтинг 3.4 (Лето 2014)
                        </a>
                    </p>
                    <p>
                        <a href="http://rating.rosnou.ru/?q=results33news" title="" target=_blank>
                            Рейтинг 3.3 (Зима 2014)
                        </a>
                    </p>
                    <p>
                        <a href="http://rating.rosnou.ru/?q=cat3-2" title="" target=_blank>
                            Рейтинг 3.2 (Лето 2013)
                        </a>
                    </p>
                    <p>
                        <a href="http://rating.rosnou.ru/?q=cat3-1" title="" target=_blank>
                            Рейтинг 3.1
                        </a>
                    </p>
                    <p>
                        <a href="http://rating.rosnou.ru/?q=top3-0" title="" target=_blank>
                            Рейтинг 3.0
                        </a>
                    </p>
                    <p>
                        <a href="http://rating.rosnou.ru/?q=node/1380" title="" target=_blank>
                            ЛШС 2012
                        </a>
                    </p>
                    <p>
                        <a href="http://rating.rosnou.ru/?q=top" title="" target=_blank>
                            Рейтинг 2.0
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
