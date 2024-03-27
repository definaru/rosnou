<?php

namespace frontend\rating\managers;

use \common\models\SiteDistrict;

class DistrictManager
{
	public $orderBy = 'title';

    public function loadDistrictList() {

        $query = SiteDistrict::find();
        $list = $query->select('title')->orderBy($this->orderBy)->indexBy('id')->column();
        
        return $list;
    }
}