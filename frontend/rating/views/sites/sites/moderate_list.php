<?php $this->breadcrumbs = ['Заявки'] ?>
<?php $this->title = 'Заявки' ?>
<?php $this->pageHeader = 'Заявки участников' ?>

<?php $this->beginBlock('pageHeaderContent') ?>

    <?php //$this->render('moderate/_search', ['siteTypes' => $siteTypes, 'searchModel' => $searchModel])?>

<?php $this->endBlock() ?>

<?php $this->beginBlock('underContent') ?>

    <nav id="pagination">
        <?=\yii\widgets\LinkPager::widget([
            'pagination' => $dataProvider->pagination,
            'nextPageLabel' => '<span aria-hidden="true">Вперед&nbsp;&nbsp;
                                        <i class="fa fa-angle-right"></i></span>',
            'prevPageLabel' => '<span aria-hidden="true"><i class="fa fa-angle-left"></i>&nbsp;&nbsp;Назад
                    </span>'
        ])?>
    </nav>

<?php $this->endBlock() ?>

<?=$this->render('moderate_list/_count', ['requestsCount' => $dataProvider->pagination->totalCount])?>

<br/>

<?=$this->render('moderate_list/_list', ['dataProvider' => $dataProvider, 'siteTypes' => $siteTypes, 'searchModel' => $searchModel])?>

<?=$this->render('moderate_list/_count', ['requestsCount' => $dataProvider->pagination->totalCount])?>
