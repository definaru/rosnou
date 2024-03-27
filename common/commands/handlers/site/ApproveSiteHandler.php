<?php

namespace common\commands\handlers\site;

use common\models\Site;
use domain\site\services\SiteService;
use common\emails\SiteApprovedMessage;
use common\components\EmailNotification;
use common\commands\site\ApproveSiteCommand;

class ApproveSiteHandler
{
    /**
     * @var SiteService
     */
    private $siteService;

    /**
     * @var EmailNotification
     */
    private $emailNotification;

    public function __construct(SiteService $siteService, EmailNotification $emailNotification)
    {
        $this->siteService = $siteService;
        $this->emailNotification = $emailNotification;
    }

    public function handle(ApproveSiteCommand $command)
    {
        $this->siteService->changeStatus($command->site(), Site::STATUS_APPROVED);

        if($command->comment()) {
            $this->siteService->createComment($command->site(), $command->user(), $command->comment(), Site::STATUS_APPROVED);
        }

        $this->emailNotification->send(new SiteApprovedMessage($command->site()));

        return $this;
    }
}