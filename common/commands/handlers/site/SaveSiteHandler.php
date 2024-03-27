<?php

namespace common\commands\handlers\site;

use common\components\EmailNotification;
use common\emails\SiteCreatedMessage;
use common\models\User;
use domain\site\services\SiteService;
use common\commands\site\SaveSiteCommand;
use yii\helpers\ArrayHelper;

class SaveSiteHandler
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

    /**
     * @param SaveSiteCommand $command
     * @param int $userId
     * @return \common\models\Site
     */
    public function handle(SaveSiteCommand $command)
    {
        if($command->site()) {
            $attributes = $command->siteForm()->attributes;

            ArrayHelper::remove($attributes, 'title');
            ArrayHelper::remove($attributes, 'domain');

            $result = $this->siteService->updateSite($command->site(), $attributes);

            if($command->comment()) {
                $this->siteService->createComment($command->site(), $command->user(), $command->comment(), $command->site()->status_index);
            }

            return $result;
        } else {
            $site = $this->siteService->createSite($command->user()->getId(), $command->siteForm()->attributes);

            // $moderator = User::find()->where('is_moderator = true')->one();

            // if($moderator) {
            //     $this->emailNotification->send(new SiteCreatedMessage($site, $moderator));
            // }

            return $site;
        }
    }
}