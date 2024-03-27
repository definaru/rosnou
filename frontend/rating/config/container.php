<?php

Yii::$container->setSingleton(\common\components\EmailNotification::class, function() {
    return new \common\components\EmailNotification(Yii::$app->mailer, Yii::$app->params['supportEmail']);
});

Yii::$container->setSingleton(common\managers\SectionManager::class, function(){
    return new common\managers\SectionManager(
        Yii::$app->user->identity
    );
});

Yii::$container->setSingleton(ScoreRulesManager::class, function(){
    return new frontend\rating\managers\ScoreRulesManager();
});

Yii::$container->setSingleton('PeriodManager', function(){
    return new frontend\rating\managers\PeriodManager();
});

Yii::$container->setSingleton('VkontakteManager', function(){

    $User = Yii::$app->user->identity;
    $Curl = new common\components\Curl();

    return new frontend\rating\managers\VkontakteManager($User, $Curl);
});

Yii::$container->setSingleton('CriteriaManager', function(){

    $User = Yii::$app->user->identity;

    return new frontend\rating\managers\CriteriaManager($User);
});

Yii::$container->set(\yii\filters\AccessControl::class, [
    'ruleConfig' => [
        'class' => \frontend\rating\components\filters\AccessRule::class,
    ],
]);

Yii::$container->set('ExportManager', function(){
    return new frontend\rating\managers\ExportManager (
        Yii::$container->get('ScoreRulesManager')
    );
});

Yii::$container->set('SiteRequestManager', function(){
    return new frontend\rating\managers\SiteRequestManager (
        Yii::$container->get('PeriodManager'),
        Yii::$app->user->identity,
        Yii::$app->db
    );
});

Yii::$container->set('RateRequestManager', function(){

    return new frontend\rating\managers\RateRequestManager (
        Yii::$container->get('PeriodManager'),
        Yii::$app->user->identity,
        Yii::$app->db,
        Yii::$container->get('VkontakteManager')
    );
});

?>