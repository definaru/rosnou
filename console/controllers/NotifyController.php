<?php

namespace console\controllers;

use common\models\Notify;
use common\models\User;
use common\managers\NotifyManager;
use yii\console\Controller;
use Yii;

class NotifyController extends Controller
{

    public $email = null;
    public $notify = null;

    public function options($actionID) {
        return [
            'email' => 'email',
            'notify' => 'notify',
        ];
    }

    public function actionTestEmail(){

        $email = $this->getParam('email');

        $result = Yii::$app->mailer->compose()
    		->setFrom('delivery@edsites.ru')
    		->setTo($email)
    		->setSubject('Subject hello')
    		->setTextBody('Text body!')
    		//->setHtmlBody('<b>текст сообщения в формате HTML</b>')
    		->send();
 
        echo "Mail send to: " . $email . '; result: ' . intval($result) . "\n";


    }

    private function getParam($name){

        if( $this->$name === null ){
            echo 'Не указан обязательный параметр --'."{$name}\n";
            die;
        }

        return $this->$name;
    }

    public function actionSendmail()
    {
    	$notify = $this->notify;
    	$email = $this->getParam('email');

    	$QueryNotify = Notify::find();

    	if($notify) {
    		$QueryNotify->andWhere(['id' => $notify]);
    	} else {
    		$QueryNotify->andWhere([
    			'sendemail_flag' => 1,
				'sendlock_flag' => 0,
				'sendfinish_flag' => 0
			]);
    	}

    	$NotifyCount = $QueryNotify->count();

    	$QueryUser = User::find();

    	if ($email != 'all') {
    		$QueryUser->andWhere(['email' => $email]);    	
    	}

    	$UserCount = $QueryUser->count();

    	if (!$UserCount) {
    		echo "Users not found!\n";
    		die;
    	}

    	$NotifyManager = new NotifyManager();

    	echo "Notifies found: $NotifyCount\n";
    	echo "Users found: $UserCount\n";

    	$Notifies = $QueryNotify->all();

    	$notify_count = 1;

   		$template = '@console/mail/notify_mail.php';

    	foreach ($Notifies as $Notify) {

    		$user_count = 1;
    		$Notify->sendlock_flag = 1;
    		$Notify->save();

    		foreach ($QueryUser->batch(100) as $Users) {
    			foreach ($Users as $User) {
    				echo "Notify: ($notify_count/$NotifyCount) User: ($user_count/$UserCount)\n";

    				// SEND EMAIL
    				$NotifyManager->sendNotify($User->email, $Notify);
    				//$this->sendMessage($template, $User->email, $Notify);
    				$user_count++;
    			}

    		}
    		
    		$Notify->sendfinish_flag = 1;
    		$Notify->save();

    		$notify_count++;
    	}
    }
}
