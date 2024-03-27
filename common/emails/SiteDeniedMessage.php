<?php

namespace common\emails;

use common\models\Site;
use domain\notification\EmailMessage;
use yii\helpers\Url;

class SiteDeniedMessage implements EmailMessage
{
    /**
     * @var Site
     */
    private $site;

    public function __construct(Site $site)
    {
        $this->site = $site;
    }

    /**
     * @return mixed
     */
    public function view()
    {
        return 'site/denied';
    }

    /**
     * @return array
     */
    public function data(): array
    {
        return [
            'link' => \yii\helpers\Url::base(true) . Url::toRoute(['sites/sites/edit', 'id' => $this->site->id]),
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
        return $this->site->user->email;
    }

    /**
     * @return string
     */
    public function subject(): string
    {
        return 'Ваш сайт отклонен';
    }
}