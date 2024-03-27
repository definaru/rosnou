<?php

namespace console\controllers;

use common\managers\AttachManager;
use common\managers\MailingManager;
use common\managers\UploadManager;
use common\models\SpMailingMessage;
use frontend\crm\forms\MailingSearchForm;
use frontend\crm\forms\PageSizeForm;
use Yii;
use yii\console\Controller;

/**
 * Dev утилиты
 */
class MailingController extends Controller
{
    /**
     * @var int Сколько рассылок обработать за раз
     */
    public $limit = 1000;
    /**
     * @var bool Принудительно отправить письма, даже если были отправлены ранее
     */
    public $force = false;

    public $email = null;

    public function options($actionID)
    {
        switch ($actionID) {
            case 'test':
                return [
                    'email' => 'email',
                ];
                break;
            case 'send':
                return [
                    'limit' => 'limit',
                    'help' => 'help'
                ];
                break;
            case 'selective-send':
                return [
                    'force' => 'force',
                    'help' => 'help'
                ];
                break;
            default:
                return [];
        }
    }

    private function getParam($name){

        if( $this->$name == false ){
            echo 'Не указан обязательный параметр --'."{$name}\n";
            die;
        }

        return $this->$name;
    }

    /**
     * Делаем рассылку куратора только по указанным идентификаторам рассылок
     * app/yii mailing/selective-send
     *
     * @param $message_id string идентификаторы рассылок через запятую (например 1,2,3)
     * @throws \Exception
     */
    public function actionSelectiveSend($message_id)
    {
        $list = explode(',', $message_id);
        if (is_array($list)) {
            foreach ($list as $message_id) {

                $searchForm = new MailingSearchForm();
                if ($this->force === true) {
                    $searchForm->setMessageSection('message');
                } else {
                    $searchForm->setMessageSection('for_delivery');
                }

                $searchForm->setMessageId($message_id);

                $pageSizeForm = new PageSizeForm();
                $pageSizeForm->page_size = 1;

                $this->send($searchForm, $pageSizeForm);
            }
        }
    }

    public function actionTest(){

        $email = $this->getParam('email');
        $body = 'Это тестовое сообщение';
        $htmlbody = "<p>{$body}</p>";

        $result = \Yii::$app->mailer->compose()
            ->setHtmlBody($htmlbody)
            ->setTextBody($body)
            ->setSubject('Сообщение для проверки работоспособности почтового сервера')
            ->setTo($email)
            ->send();

        echo "Mail send to: " . $email . '; result: ' . intval($result) . "\n";
    }

    /**
     * Делаем рассылку куратора
     * app/yii mailing/send
     */
    public function actionSend()
    {
        $searchForm = new MailingSearchForm();
        $searchForm->setMessageSection('for_delivery');

        $pageSizeForm = new PageSizeForm();
        $pageSizeForm->page_size = $this->limit;

        $this->send($searchForm, $pageSizeForm);
    }

    /**
     * @param MailingSearchForm $searchForm
     * @param PageSizeForm $pageSizeForm
     * @throws \Exception
     */
    private function send(MailingSearchForm $searchForm, PageSizeForm $pageSizeForm)
    {
        /**
         * @var MailingManager $MailingManager
         * @var UploadManager $UploadManager
         * @var AttachManager $AttachManager
         */
        $MailingManager = new MailingManager();
        $UploadManager = new UploadManager();
        $AttachManager = new AttachManager(
            null,
            null,
            Yii::$app->logger,
            $UploadManager
        );

        // Извлекаем сообщения с просроченной датой отправки
        $MessageList = $MailingManager->loadMailList($searchForm, $pageSizeForm);
        echo "Найдено " . $MessageList['count'] . " рассылок для отправки\n";

        /**
         * @var SpMailingMessage $Message
         */
        foreach ($MessageList['list'] as $iteration => $Message) {

            $pageSizeForm = new PageSizeForm();
            $pageSizeForm->page_size = $MailingManager->staticUserCount($Message);

            $UserList = $MailingManager->staticUserList($Message, $pageSizeForm);
            echo "Найдено " . $UserList['count'] . " получатетелей рассылки #" . $iteration . ", id:" . $Message->id . " \n";

            $attachment_list = $AttachManager->arrangeMailMessageAttachList($Message,'console');

            $MailingManager->beforeSendMessage($Message);
            foreach ($UserList['list'] as $User) {
                try {
                    $result = $MailingManager->sendMessage(Yii::$app->mailer, $Message, $attachment_list, $User['email']);
                    switch ($result) {
                        case 'already':
                            echo "Mail already sent to: " . $User['email'] . ';' . "\n";
                            break;
                        case false:
                            $User->attempt_datetime = date('Y-m-d H:i:s');
                            $User->attempt = ($User->attempt + 1);
                            $User->attempt_log = "Mail don't send to: " . $User['email'];
                            $User->save();
                            Yii::error("Mail don't send to: " . $User['email']);
                            echo "Mail don't send to: " . $User['email'] . ";\n";
                            break;
                        default:
                            $User->sending_datetime = date('Y-m-d H:i:s');
                            $User->save();
                            echo "Mail is send to: " . $User['email'] . '; result: ' . intval($result) . "\n";
                    }
                } catch (\Exception $exception) {

                    Yii::error($exception->getMessage());
                    echo $exception->getMessage() . "\n";

                    try {
                        $User->attempt_datetime = date('Y-m-d H:i:s');
                        $User->attempt = ($User->attempt + 1);
                        $User->attempt_log = $exception->getMessage();
                        $User->save();
                    } catch (\Exception $save_exception) {
                        Yii::error($save_exception->getMessage());
                        echo $save_exception->getMessage() . "\n";
                    }
                }

            }
            $MailingManager->afterSendMessage($Message);
            echo "Всего было отправлено {$Message->user_count} писем из " . $UserList['count'] . " (id:" . $Message->id . ")\n";
        }
    }

}
