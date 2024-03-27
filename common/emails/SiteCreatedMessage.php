<?php

namespace common\emails;

use common\models\User;
use yii\helpers\Url;
use common\models\Site;
use domain\notification\EmailMessage;

class SiteCreatedMessage implements EmailMessage
{
    /**
     * @var Site
     */
    private $site;

    /**
     * @var User
     */
    private $moderator;

    public function __construct(Site $site, User $moderator)
    {
        $this->site = $site;
        $this->moderator = $moderator;
    }

    /**
     * @return mixed
     */
    public function view()
    {
        return 'site/created';
    }

    /**
     * @return array
     */
    public function data(): array
    {
        return [
            'link' => \yii\helpers\Url::base(true) . Url::toRoute(['sites/sites/moderate-edit', 'id' => $this->site->id]),
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
        return $this->moderator->email;
    }

    /**
     * @return string
     */
    public function subject(): string
    {
        return 'Заявлен новый сайт';
    }
}