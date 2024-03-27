<?php $this->breadcrumbs[] = 'Почта не подтверждена' ?>
<?php $this->title = 'Почта не подтверждена' ?>

<div class="row">
    <div class="col-xs-6 col-xs-offset-3">
        <p>
            Email не активирован
        </p>
        <p>
            <a class="btn btn-blue" style="vertical-align: top;" href="<?=\yii\helpers\Url::toRoute(['users/access/verification-email-resent'])?>">Отправить активацию повторно</a>
        </p>
    </div>
</div>