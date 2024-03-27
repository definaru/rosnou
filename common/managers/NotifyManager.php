<?php

namespace common\managers;

use yii\db\Expression;
use common\models\Notify;
use Yii;

class NotifyManager
{

     /*
     * Устанавливает флаг активности у периода
     */
    public function setActiveFlag(Notify $model)
    {
        if ($model->active_flag) {
            // UPDATE active_flag
            $connection = \Yii::$app->db;
            $connection->createCommand()->update(\common\models\Notify::tableName(), ['active_flag' => 0], ['<>', 'id', $model->id])->execute();
        }
    }

    public function getActiveNotify() {

    	$query = Notify::find()->where(['active_flag' => 1]);
    	$query->andWhere(['>', 'finish_datetime', new Expression('NOW()')]);

    	$Notify = $query->one();

    	return $Notify;

    }

    public function sendNotify($email, $Notify){

        $html = Yii::$app->mailer->render('@common/mail/notify_mail',['Notify' => $Notify]);

        try {        
            $result = \Yii::$app->mailer->compose()
                ->setHtmlBody($html)
                ->setSubject($Notify->title)
                ->setTo($email)
                ->setFrom(['noreply@rating-web.ru' => 'Общероссийский рейтинг образовательных сайтов'])
               ->send();
        } catch(\Swift_RfcComplianceException $e){
            echo "Address ({$email}) does not comply with RFC 2822!\n";
        }

    }
}
