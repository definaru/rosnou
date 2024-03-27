<?php

namespace domain\site\services;

use common\models\SiteComment;
use common\models\User;
use domain\Exception;
use common\models\Site;
use domain\ModelSaveException;
use frontend\rating\forms\SiteForm;

class SiteService
{
    /**
     * @var int Ограничение на количество сайтов. Чтобы не было злоупотреблений
     */
    private $sitesLimit;

    public function __construct(int  $sitesLimit = 100)
    {
        $this->sitesLimit = $sitesLimit;
    }

    /**
     * @param $userId
     * @param array $data
     * @return Site
     * @throws Exception
     * @throws ModelSaveException
     */
    public function createSite($userId, array $data)
    {
        if($this->userExceedsSitesLimit($userId)) {
            throw new Exception("User exceeds sites limit");
        }

        $site = new Site();

        $site->setAttributes($data);

        $site->user_id = $userId;
        $site->status_index = Site::STATUS_ON_MODERATION;
        $site->created_timestamp = date('Y-m-d H:i:s');
        $site->have_ads = (bool)$site->have_ads;

        $this->setQueueIndex($site);

        if(!$site->save()) {
            throw new ModelSaveException($site);
        }

        return $site;
    }

    /**
     * @param Site $site
     * @param array $data
     * @return Site
     * @throws ModelSaveException
     */
    public function updateSite(Site $site, array $data)
    {
        $site->setAttributes($data);

        $site->have_ads = (bool)$site->have_ads;

        if($site->isStatus(Site::STATUS_DENIED)) {
            $site->setStatus(Site::STATUS_ON_MODERATION);
        }

        if(!$site->save()) {
            throw new ModelSaveException($site);
        }

        return $site;
    }

    /**
     * @param Site $site
     * @param int $newStatus
     * @return Site
     * @throws ModelSaveException
     */
    public function changeStatus(Site $site, int $newStatus)
    {
        $site->setStatus($newStatus);

        if(!$site->save()) {
            throw new ModelSaveException($site);
        }

        return $site;
    }

    /**
     * @param int $userId
     * @return int|string
     */
    private function userExceedsSitesLimit(int $userId)
    {
        return Site::find()->byUserId($userId)->count() > $this->sitesLimit;
    }

    /**
     * @param Site $site
     * @param User $user
     * @param string $text
     * @param int $siteStatus
     * @return SiteComment
     * @throws ModelSaveException
     */
    public function createComment(Site $site, User $user, string $text, int $siteStatus)
    {
        $siteComment = new SiteComment();
        $siteComment->site_id = $site->id;
        $siteComment->user_id = $user->id;
        $siteComment->body = $text;
        $siteComment->site_status_index = $siteStatus;
        $siteComment->created_datetime = date('Y-m-d H:i:s');

        if(!$siteComment->save()) {
            throw new ModelSaveException($siteComment);
        }

        return $siteComment;
    }

    /**
     * @param Site $site
     * @return $this
     */
    private function setQueueIndex(Site $site)
    {
        $site->queue_index = Site::find()->max('queue_index') + 1;

        return $this;
    }
}