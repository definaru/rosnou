<?php
namespace frontend\rating\managers;

use Yii;

use common\models\User;
use common\models\Site;
use common\models\RateRequest;
use common\models\RatePeriod;
use common\models\RateCriteriaType;
use common\models\RateCriteria;

use yii\helpers\ArrayHelper;
use yii\db\ActiveQuery;
use yii\db\Query;

class CriteriaManager {

    private $User = null;

    public function __construct(User $User) {
        $this->User = $User;
    }

}
