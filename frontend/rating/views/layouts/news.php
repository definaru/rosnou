<?php $this->pageHeader = 'Новости'?>

<?php $this->beginBlock('pageHeaderContent')?>

    <div class="header-addon">
        <form class="form-inline dimox" method="post" name="sbs_frm" enctype="multipart/form-data" action="/dispatches/subscribedo/">
            <div class="form-group">
                <label class="h3 small"><i class="fa fa-envelope fa-left"></i>Подписка</label><input name="sbs_mail" type="email" class="form-control" id="" placeholder="Email" value="almost@inbox.ru">
            </div>
            <button type="submit" class="btn btn-blue">Подписаться</button>
        </form>
    </div>

<?php $this->endBlock()?>

<?php $this->beginContent('@app/views/layouts/page.php') ?>

    <?=$content?>

<?php $this->endContent() ?>