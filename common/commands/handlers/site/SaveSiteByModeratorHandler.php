<?php

namespace common\commands\handlers\site;

use domain\site\services\SiteService;
use common\commands\site\SaveSiteCommand;
use common\commands\site\SaveSiteByModeratorCommand;

class SaveSiteByModeratorHandler
{
    /**
     * @var SiteService
     */
    private $siteService;

    public function __construct(SiteService $siteService)
    {
        $this->siteService = $siteService;
    }

    /**
     * @param SaveSiteCommand $command
     * @param int $userId
     * @return \common\models\Site
     */
    public function handle(SaveSiteByModeratorCommand $command)
    {
        return $this->siteService->updateSite($command->site(), $command->siteForm()->attributes);
    }
}