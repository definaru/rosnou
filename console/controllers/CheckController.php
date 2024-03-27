<?php

namespace console\controllers;

use yii\console\Controller;
use common\models\Site;

class CheckController extends Controller
{
    public function actionDouble()
    {
        $exclude = [
            'edu.tatar.ru',
            'portal.iv-edu.ru',
            'sites.google.com',
            'www.koipkro.kostroma.ru',
            'www.eduportal44.ru]',
            'nsportal.ru',
        ];
        
        $doubles = [];

        $query = Site::find();
        
        foreach ($query->batch(100) as $Sites) {
            
            foreach ($Sites as $Site) {

                $domain = parse_url($Site->domain, PHP_URL_HOST);
                
                if( empty($domain) ){
                    continue;
                }

                $queryDoubles = Site::find()
                    ->where(['similar to', 'domain', '(http|https)://'.$domain.'%']);

                $sql = $queryDoubles->createCommand()->rawSql;

                $Doubles = $queryDoubles->all();

                if ( sizeof($Doubles) > 1 && !in_array($domain, $exclude) ) {
                    
                    if (!isset($doubles[$domain])) {

                        foreach ($Doubles as $Double) {

                            $doubles[$domain]['sql'] = $sql;  
                            $doubles[$domain]['domain'] = $domain;                    
                            $doubles[$domain]['list'][] = [
                                'id' => $Double->id, 
                                'domain' => $Double->domain
                            ];
                        }
                    }
                }

            }

            //break;

         }

        $index = 1;
        foreach ($doubles as $domain => $data) {

            echo "$index ---- \n";
            echo "Sql: {$data['sql']}\n";

            foreach ($data['list'] as $value) {
                echo "{$value['id']} {$value['domain']}\n";
            }

            $index++;
        }

    }
}