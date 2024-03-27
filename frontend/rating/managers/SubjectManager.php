<?php

namespace frontend\rating\managers;

use \common\models\SiteSubject;

class SubjectManager
{
	public $orderBy = 'title';

    public function loadSubjectList() {

        $query = SiteSubject::find();
        $list = $query->select('title')->orderBy($this->orderBy)->indexBy('id')->column();
        
        return $list;
    }
}