<?php

namespace common\emails;

use common\models\Site;
use common\models\User;
use domain\notification\EmailMessage;

class SiteExaminationFinishedMessage implements EmailMessage
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var Site
     */
    private $site;

    public function __construct(User $user, Site $site)
    {
        $this->user = $user;
        $this->site = $site;
    }

    /**
     * @return mixed
     */
    public function view()
    {
        return 'user/site_examination_finished';
    }

    /**
     * @return array
     */
    public function data(): array
    {
        return [
            'site' => $this->site,
            'user' => $this->user,
        ];
    }

    /**
     * @return string
     */
    public function from(): string
    {
        return false;
    }

    /**
     * @return mixed
     */
    public function to()
    {
        return $this->user->email;
    }

    /**
     * @return string
     */
    public function subject(): string
    {
        return 'Экспертиза вашего сайта завершена, для получения подробной информации пройдите по ссылке.';
    }
}