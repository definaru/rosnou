<?php

namespace frontend\rating\managers;

use \common\models\Site;

class MapManager
{

    public function loadSubjectList()
    {

        $query = Site::find()->alias('s')->where(['s.status_index' => Site::STATUS_APPROVED]);
        $query->andWhere(['!=', 'sbj.map_code', '']);
        $query->andWhere(['is not','sbj.map_code',null]);
        $query->joinWith('subject sbj');
        $query->select('count(*) as total, sbj.map_code, sbj.id');
        $query->groupBy(['s.subject_id', 'sbj.map_code', 'sbj.id']);

        $Subjects = $query->createCommand()->queryAll();

        $list = [];

        $totalCountSites = 0;
        $totalCountSubjects = 0;

        foreach ($Subjects as $item) {
        	$totalCountSites += $item['total'];
        	$totalCountSubjects ++;
            if ($item['map_code']) {
                $list['list'][$item['map_code']] = ['subject_id' => $item['id'],'code' => $item['map_code'], 'total' => $item['total'], 'text' => $this->getUnitCase($item['total'],'участник', 'участника', 'участников')];
            }
        }

        $list['totalCountSites']['count'] = $totalCountSites;
        $list['totalCountSites']['text'] = $this->getUnitCase($totalCountSites, 'сайт', 'сайта', 'сайтов');

        $list['totalCountSubjects']['count'] = $totalCountSubjects;
        $list['totalCountSubjects']['text'] = $this->getUnitCase($totalCountSubjects,'регион', 'региона', 'регионов');

        return $list;
    }

    public function getUnitCase($value, $unit1, $unit2, $unit3)
    {
        $value = abs((int) $value);
        if (($value % 100 >= 11) && ($value % 100 <= 19)) {
            return $unit3;
        } else {
            switch ($value % 10) {
                case 1:
                    return $unit1;
                case 2:case 3:case 4:
                    return $unit2;
                default:
                    return $unit3;
            }
        }
    }
}
