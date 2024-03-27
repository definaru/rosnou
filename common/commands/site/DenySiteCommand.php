<?php

namespace common\commands\site;

use common\models\RateRequest;
use common\models\Site;
use common\models\User;

class DenySiteCommand
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

    public function __construct(Site $site, string $comment, User $user)
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
     * @return string
     */
    public function comment() : string
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