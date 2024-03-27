<?php

namespace common\commands\site;

use common\models\Site;
use common\models\User;
use frontend\rating\forms\SiteForm;

class SaveSiteByModeratorCommand
{
    /**
     * @var User
     */
    private $moderator;

    /**
     * @var Site
     */
    private $site;

    /**
     * @var SiteForm
     */
    private $siteForm;

    public function __construct(User $moderator, Site $site, SiteForm $siteForm)
    {
        $this->moderator = $moderator;
        $this->site = $site;
        $this->siteForm = $siteForm;
    }

    /**
     * @return int
     */
    public function moderator() : User
    {
        return $this->moderator;
    }

    /**
     * @return Site
     */
    public function site() : Site
    {
        return $this->site;
    }

    /**
     * @return SiteForm
     */
    public function siteForm() : SiteForm
    {
        return $this->siteForm;
    }
}