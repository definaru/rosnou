<?php

namespace frontend\rating\managers;

use \common\models\SiteType;

class SiteTypeManager
{
	public $orderBy = 'title';

    public function loadSiteTypesList() {

        $query = SiteType::find();
        $list = $query->select('title')->orderBy($this->orderBy)->indexBy('id')->column();
        
        return $list;
    }

        public function loadSiteTypesListSlug() {

        $query = SiteType::find();
        $list = $query->select('title')->orderBy($this->orderBy)->indexBy('slug')->column();
        
        return $list;
    }
}