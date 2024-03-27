<?php

namespace common\commands\site;

use common\models\Site;
use common\models\User;
use frontend\rating\forms\SiteForm;

class SaveSiteCommand
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var Site
     */
    private $site;

    /**
     * @var SiteForm
     */
    private $siteForm;

    /**
     * @var string
     */
    private $comment;

    public function __construct(User $user, Site $site = null, SiteForm $siteForm, string $comment = null)
    {
        $this->user = $user;
        $this->site = $site;
        $this->siteForm = $siteForm;
        $this->comment = $comment;
    }

    /**
     * @return int
     */
    public function user() : User
    {
        return $this->user;
    }

    /**
     * @return Site
     */
    public function site()
    {
        return $this->site;
    }

    /**
     * @return SiteForm
     */
    public function siteForm()
    {
        return $this->siteForm;
    }

    /**
     * @return mixed
     */
    public function comment()
    {
        return $this->comment;
    }
}