<div class="row">
    <div class="col-xs-6">
        <h3><?=\Yii::t('app', 'В очереди {n, plural, =0{нет заявок} =1{# заявка} one{# заявка} few{# заявки} many{# заявок} other{# заявки}}', [
            'n' => $requestsCount,
        ])?></h3>
    </div>
</div>