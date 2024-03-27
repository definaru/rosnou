<?php

namespace common\commands\handlers\site;

use common\models\Site;
use common\emails\SiteDeniedMessage;
use domain\site\services\SiteService;
use common\components\EmailNotification;
use common\commands\site\DenySiteCommand;

class DenySiteHandler
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

    public function handle(DenySiteCommand $command)
    {
        $this->siteService->changeStatus($command->site(), Site::STATUS_DENIED);

        $this->siteService->createComment($command->site(), $command->user(), $command->comment(), Site::STATUS_APPROVED);

        $this->emailNotification->send(new SiteDeniedMessage($command->site()));

        return $this;
    }
}