<?php

namespace common\components;

use yii\mail\MailerInterface;
use domain\notification\EmailMessage;

class EmailNotification
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @var string
     */
    private $fromEmail;

    public function __construct(MailerInterface $mailer, string $fromEmail)
    {
        $this->mailer = $mailer;
        $this->fromEmail = $fromEmail;
    }

    /**
     * @param EmailMessage $message
     * @return bool
     */
    public function send(EmailMessage $message)
    {
        return $this->mailer->compose($message->view(), $message->data())
            ->setFrom($message->from() ?: $this->fromEmail)
            ->setTo($message->to())
            ->setSubject($message->subject())
            ->send();
    }
}