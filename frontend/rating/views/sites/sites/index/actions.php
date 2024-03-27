<?php if($site['site_status_approved']):?>

    <?php if($activePeriod):?>
        <?php if($site['request_not_finished']):?>
            <a href="<?=\yii\helpers\Url::toRoute(['users/examination/index', 'id' => $site['id']])?>">
                провести самообследование
            </a>
        <?php elseif($site['request_user_draft']):?>
            <a href="<?=\yii\helpers\Url::toRoute(['users/examination/index', 'id' => $site['id']])?>">
                продолжить самообследование
            </a>
        <?php elseif($site['request_checked']):?>
            <a href="<?=\yii\helpers\Url::toRoute(['users/examination/results', 'id' => $site['id']])?>">
                посмотреть результаты
            </a>
        <?php endif?>
    <?php endif?>

<?php else:?>
    <a href="<?=\yii\helpers\Url::toRoute(['sites/sites/edit', 'id' => $site['id']])?>">изменить</a>
<?php endif?>