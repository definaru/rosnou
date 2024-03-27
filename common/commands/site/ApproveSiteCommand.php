<?php

namespace common\commands\site;

use common\models\RateRequest;
use common\models\Site;
use common\models\User;

class ApproveSiteCommand
{
    /**
     * @var Site
     */
    private $site;

    /**
     * @var string
     */
    private $comment;

    /**
     * @var User
     */
    private $user;

    public function __construct(Site $site, string $comment = null, User $user = null)
    {
        $this->site = $site;
        $this->comment = $comment;
        $this->user = $user;
    }

    /**
     * @return RateRequest
     */
    public function site(): Site
    {
        return $this->site;
    }

    /**
     * @return string|null
     */
    public function comment()
    {
        return $this->comment;
    }

    /**
     * @return User
     */
    public function user() : User
    {
        return $this->user;
    }
}