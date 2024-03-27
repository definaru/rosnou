<?php

namespace console\controllers;

use yii\console\Controller;
use common\models\SiteDistrict;
use common\models\SiteSubject;
use common\models\User;
use common\models\Site;
use common\models\SiteType;
use common\models\News;
use common\models\RateRequest;
use common\models\RateCriteriaType;
use common\models\RateCriteria;
use common\models\RateCriteriaResult;
use common\components\Curl;
use yii\db\Query;
use Yii;

class DataController extends Controller
{
    // импорт субъектов федерации 
    // !!!! ВАЖНО !!!! сначала делаем импорт округов actionImportDistricts
    // файл:     data/subject.txt
    // фоормат:    422;"Москва";"Центральный";"msk";
    public function actionImportSubjects()
    {

        $file = __DIR__ . "/../../../data/subject.txt";

        echo "File: $file\n";

        $handle = fopen($file, "r");

        while ($data = fgets($handle)) {

            $subject_data = explode(';', $data);

            if (is_numeric($subject_data[0])) {

                $subject_data[1] = str_replace('"', '', $subject_data[1]); // субъект
                $subject_data[2] = str_replace('"', '', $subject_data[2]); // округ
                $subject_data[3] = str_replace('"', '', $subject_data[3]); // код для карты

                $District = SiteDistrict::findOne(['title' => $subject_data[2]]); 

                if (!$District) {
                	echo "ERROR: District not found: '{$subject_data[2]}'\n";
                }

                $Subject = SiteSubject::findOne(['title' => $subject_data[1]]);

                if (!$Subject) {
                	$Subject = new SiteSubject();
                	$Subject->id = $subject_data[0];
                	$Subject->title = $subject_data[1];
                	$Subject->district_id = $District->id;
                	$Subject->map_code = $subject_data[3];
                	$Subject->save();

                	echo "Subject created: '{$subject_data[1]}'\n";
                }

            }
        }

        fclose($handle);
    }

    // импорт федеральных округов
    // файл:     data/district.txt
    // фоормат:    419;"Крымский";
    public function actionImportDistricts()
    {

        $file = __DIR__ . "/../../../data/district.txt";

        echo "File: $file\n";

        $handle = fopen($file, "r");

        while ($data = fgets($handle)) {

            $subject_data = explode(';', $data);

            if (is_numeric($subject_data[0])) {

            	// $subject_data[0] - ID
                $subject_data[1] = str_replace('"', '', $subject_data[1]); // наименование 


                $District = SiteDistrict::findOne(['title' => $subject_data[1]]); 

                if (!$District) {
                	$District = new SiteDistrict();
                	$District->id = $subject_data[0];
                	$District->title = $subject_data[1];
                	$District->save();

                	echo "District created: '{$subject_data[1]}'\n";
                }

            }
        }

        fclose($handle);
    }

    // импорт пользователей
    // файл:     data/import/users.csv
    // фоормат:    "Id";"Название";"e-mail";"fname";"father_name";"lname";"org_name";"dolzhnost";"password";"groups";"photo";"is_activated";"register_date";
    public function actionImportUsers()
    {
        $file = __DIR__ . "/../../../data/import/users.csv";

        echo "File: $file\n";

        $handle = fopen($file, "r");
        while ($data = fgets($handle)) {

            $data = explode(';', $data);

            if (is_numeric($data[0])) {

                if ($data[0] == '34569') {
                    continue;
                }

                foreach ($data as $key => $value) {
                    if (mb_substr($data[$key],0,1) == '"') {
                        $data[$key] = mb_substr($data[$key],1);
                    }
                    if (mb_substr($data[$key],-1) == '"') {
                        $data[$key] = mb_substr($data[$key],0,-1);
                    }

                }

                if (mb_strpos($data[2], ',')) {
                    $email = explode(',', $data[2]);
                    $data[2] = $email[0];
                }

                $User = User::findOne($data[0]);

                $msg = 'updated';

                if (!$User) {
                    $User = new User();
                    $User->id = $data[0];
                    $msg = 'added';
                } 

                $User->password_hash = $data[8];
                $User->lastname = $data[5];
                $User->firstname = $data[3];
                $User->middlename = $data[4];
                $User->orgname = $data[6];
                $User->position = $data[7];
                $data[10] = str_replace('/images/avatars/', '', $data[10]);
                $User->avatar_image = $data[10];
                $User->email = $data[2];
                $User->login = $data[1];

                if ($data[11]) {
                    $User->email_verified = 1;
                } else {
                    $User->email_verified = 0;    
                }


                if ($data[9] == 'Модераторы') {
                    $User->is_moderator = 1;
                } else {
                    $User->is_moderator = 0;   
                }

                if ($data[9] == 'Эксперты') {
                    $User->is_expert = 1;
                } else {
                    $User->is_expert = 0;
                }

                if ($data[9] == 'Супервайзеры') {
                    $User->is_admin = 1;
                } else {
                    $User->is_admin = 0;
                }


                if (!$User->save(false)) {
                    print_r($User->errors);
                    die;
                } 

                echo "User {$msg} id = {$data[0]} email = {$data[2]}\n";

            }
        }

        fclose($handle);
    }

    // импорт сайтов
    // файл:     data/import/sites.csv
    // фоормат:    
    // "Id";"Название";"site_category";"site_address";"status";"user_id";"site_full_name";"federal_district";"subject_federation";"locality";"fio_direktora";"site_email";"no_ad";"site_name";
    public function actionImportSites()
    {
        $file = __DIR__ . "/../../../data/import/sites.csv";

        echo "File: $file\n";

        $handle = fopen($file, "r");
        $status_error = 0;
        while ($data = fgets($handle)) {


            $data = str_getcsv($data, ";");

            if (is_numeric($data[0])) {


                $Site = Site::findOne($data[0]);

                $msg = 'updated';

                if (!$Site) {
                    $Site = new Site();
                    $Site->id = $data[0];
                    $msg = 'added';
                } 

                if ($data[2] == '4. Персональный сайт' || $data[2] == '5. Сайт класса') {
                    $data[2] = '4. Сайт школьной тематики';
                }

                $SiteType = SiteType::findOne(['title' => $data[2]]);

                if (!$SiteType) {
                    echo "SiteType not found: {$data[2]} {$data[1]}\n";
                } else {
                    $Site->type_id = $SiteType->id;
                }

                $SiteDistrict = SiteDistrict::findOne(['title' => $data[7]]);



                if (!$SiteDistrict && $data[7]) {
                    echo "SiteDistrict not found: '{$data[7]}' {$data[3]}\n";
                } else {
                    if ($data[7]) {
                        $Site->district_id = $SiteDistrict->id;
                    }
                }

                $SiteSubject = SiteSubject::findOne(['title' => $data[8]]);

                if (!$SiteSubject && $data[8]) {
                    echo "SiteSubject not found: '{$data[8]}' {$data[3]}\n";
                } else {
                    if ($data[8]) {
                        $Site->subject_id = $SiteSubject->id;    
                    }
                }

                $User = User::findOne(['login' => $data[5]]);

                if (!$User && $data[8]) {
                    echo "User not found: '{$data[5]}' {$data[3]}\n";
                    die;
                }


                $Site->domain = $data[3];

                if ($data[4] == 'одобрена' || $data[4] == 'сайт проверен' || $data[4] == 'проходит самообследование') {
                    $Site->status_index = Site::STATUS_APPROVED;                    
                } elseif ($data[4] == 'отклонена') {
                    $Site->status_index = Site::STATUS_DENIED;                    
                } elseif ($data[4] == 'на модерации') {
                    $Site->status_index = Site::STATUS_ON_MODERATION;                    
                } else {
                    echo "STATUS not found: {$data[4]}\n";
                    $status_error++;
                }

                $Site->title = $data[13];
                $Site->user_id = $User->id;
                $Site->org_title = $data[6];
                $Site->location = $data[9];
                $Site->headname = $data[10];
                $Site->headname_email = $data[11];

                if (!$data[12]) {
                    $Site->have_ads = 1;
                }
                

                if (!$Site->save(false)) {
                    print_r($Site->errors);
                    die;
                } 

                echo "Site {$msg} id = {$data[0]} address = {$data[3]}\n";

            }
        }
        echo "ERROR STATUS COUNT: $status_error\n";
        fclose($handle);
    }

    // импорт новостей
    // файл:     data/import/news.csv
    // фоормат:    
    // id1;id2;id3;...
    public function actionImportNews()
    {

        $file = __DIR__ . "/../../../data/import/news.csv";

        echo "File: $file\n";

        $Str = file_get_contents($file);

        $IDs = explode(';',$Str);

        $Curl = new Curl();

        foreach ($IDs as $ID) {

            if (is_numeric($ID)) {
                $string = $Curl->get('http://www.rating-web.ru/upage/'.$ID.'.json');

                $data = json_decode($string, true);
                
                $News = News::findOne($ID);
                $msg = 'updated';



                if (!$News) {
                    $News = new News();
                    $News->id = $ID;
                    $msg = 'added';
                } 

                $date = $this->getPropertyByName($data['page']['properties']['group'],'publish_time','unix-timestamp');
                $date_created = date('Y-m-d H:i:s',$date );

                $News->title = $this->getPropertyByName($data['page']['properties']['group'],'h1');
                $News->slug = $data['page']['alt-name'];
                $News->preview = $this->getPropertyByName($data['page']['properties']['group'],'anons');
                $News->content = $this->getPropertyByName($data['page']['properties']['group'],'content');
                $News->publish_date = $date_created;
                $News->created_at = $date_created;
                $News->updated_at = $date_created;
                $News->is_published = $data['page']['is-active'] ?? 0;
                $News->meta_keywords = $this->getPropertyByName($data['page']['properties']['group'],'tags');
                $views_count = $this->getPropertyByName($data['page']['properties']['group'],'views');
                $News->views_count =  $views_count ? 1 : 0;


                if (!$News->save()) {
                    print_r($News->errors);
                    die;
                } 

                echo "News {$msg} id = {$ID}\n";

            }
        }
        $this->changeSequence('tbl_news','tbl_news_id_seq');
    }

    // импорт оценок
    public function actionImportRateRequests()
    {
        $PeriodIDs = ['2017' => ['curl' => 42883, 'id' => 1], '2016' => ['curl' => 23781, 'id' => 4]]; // константы для переиодов в новой базе (т.к. не совпадают имена)

        $Query = Site::find();

        // мало баллов 75, вместо 100
        // http://rating.dev.edsites.ru/uchastniki/7665?period=1
        // 
        $Query->andWhere(['or',
                //// ['like','lower(domain)','sch12satka.educhel.ru'],
                // ['like','lower(domain)','ozgdou20.edumsko.ru'],
                ['like','lower(domain)','lenschmolokovo.edumsko.ru'],
               // ['like','lower(domain)','dou132.edusev.ru'],
                // ['like','lower(domain)','ppds6.edumsko.ru'],
        ]);

        $Curl = new Curl();
        $array = [];
        $count = $Query->count();

        $i = 1;
        foreach ($Query->batch(100) as $Sites) {
            foreach ($Sites as $Site) {

                foreach ($PeriodIDs as $Period) {
                    //http://www.rating-web.ru/udata/content/getResult/$period_id/$site_id.json
                    $url = "http://www.rating-web.ru/udata/content/getResult/{$Period['curl']}/{$Site->id}.json";
                    $string = $Curl->download($url);
                    $data = json_decode( $string, JSON_UNESCAPED_UNICODE );

                    //var_dump($string);

                    $result = $data['result'] ?? 0;

                    if ($result == 1) {
                        continue;
                    }

                    if ($result == 0) {
                        echo "result = 0 siteID: {$Site->id}\n";
                        echo "$url\n";
                        continue;
                    }

                    // не выдает некоторые показатели
                    // например:

                    // "name": "yuzabiliti_udobstvo_polzovaniya_i_polnota_informacii"
                    // "name": "kachestvo_teksta"

                    // Для сравнения:
                    // http://www.rating-web.ru/udata/data/getEditFormModerator/36099.json (incorrect)
                    // http://www.rating-web.ru/udata/data/getEditFormModerator/24038.json

                    $url = "http://www.rating-web.ru/udata/data/getEditFormModerator/$result.json";

                    echo $url . "\n";

                    $string = $Curl->download($url);
                    $data = json_decode( $string, JSON_UNESCAPED_UNICODE );

                    $groups = $data['group'] ?? [];

                    //var_dump($string);
                    //var_dump($groups);

                    $keyUnset = null;

                    foreach ($groups as $key => $value) {
                        if ($value['name'] == 'props') {
                            $keyUnset = $key;
                        }
                    }

                    if ($keyUnset) {
                        unset($groups[$keyUnset]);
                    }


                    // RateRequest fill
                    $request_score = 0;
                    $msg = 'updated';
                    $RateRequest = RateRequest::findOne(['site_id' => $Site->id, 'period_id' => $Period['id']]);

                    if (!$RateRequest) {
                        $RateRequest = new RateRequest();
                        $RateRequest->site_id = $Site->id;
                        $RateRequest->period_id = $Period['id'];
                        $msg = 'added';
                    } 

                    $RateRequest->status_index = RateRequest::STATUS_CHECKED;


                    if (!$RateRequest->save()) {
                        print_r($RateRequest->errors);
                        die;
                    } 

                    echo "RateRequest {$msg} id = {$RateRequest->id} SiteID: {$Site->id}\n";

                    if (sizeof($groups)  > 0) {
                        foreach ($groups as $criteriaType) {
                             //echo "{$criteriaType['name']}\n";

                        // RateCriteriaType fill
                        $RateCriteriaType = RateCriteriaType::findOne([
                            'site_type_id' => $Site->type_id, 
                            'period_id' => $Period['id'], 
                            'sysname' => $criteriaType['name'],
                        ]);

                        $msgGroup = 'updated';

                        if (!$RateCriteriaType) {
                            $RateCriteriaType = new RateCriteriaType();

                            $RateCriteriaType->site_type_id =  $Site->type_id;
                            $RateCriteriaType->period_id = $Period['id'];
                            $RateCriteriaType->sysname =  $criteriaType['name'];
                            $msgGroup = 'added';
                        } 

                        $RateCriteriaType->title = $criteriaType['title']; 

                        if (!$RateCriteriaType->save()) {
                            print_r($RateCriteriaType->errors);
                            die;
                        } 

                        echo "RateCriteriaType {$msgGroup} id = {$RateCriteriaType->id}\n";

                            foreach ($criteriaType['field'] as $criteria) {
                                //echo "\t{$criteria['title']}\n";

                                // RateCriteria fill
                                $RateCriteria = RateCriteria::findOne([
                                    'type_id' => $RateCriteriaType->id, 
                                    'sysname' => $criteria['name'], 
                                ]);

                                $msgCrit = 'updated';

                                if (!$RateCriteria) {
                                    $RateCriteria = new RateCriteria();

                                    $RateCriteria->type_id = $RateCriteriaType->id;
                                    $RateCriteria->sysname = $criteria['name'];
                                    $msgCrit = 'added';
                                } 

                                $RateCriteria->title = $criteria['title']; 
                                $RateCriteria->score = $criteria['tip']; 

                                if (!$RateCriteria->save()) {
                                    print_r($RateCriteria->errors);
                                    die;
                                } 

                                echo "RateCriteria {$msgCrit} id = {$RateCriteria->id}\n";

                                // RateCriteriaResult fill
                                $RateCriteriaResult = RateCriteriaResult::findOne([
                                    'request_id' => $RateRequest->id, 
                                    'criteria_id' => $RateCriteria->id, 
                                ]);

                                $msgCrit = 'updated';

                                if (!$RateCriteriaResult) {
                                    $RateCriteriaResult = new RateCriteriaResult();

                                    $RateCriteriaResult->request_id = $RateRequest->id;
                                    $RateCriteriaResult->criteria_id = $RateCriteria->id;
                                    $msgCrit = 'added';
                                } 

                                $score = $criteria['value'] ?? 0;  
                                
                                //if ($Site->id == 7665) { // http://lenschmolokovo.edumsko.ru/ 
                                //    if (!is_numeric($score) && strlen($score) > 0) {
                                //        $score = $criteria['tip'];
                                //    }
                                //}
                                
                                $status_index = $score > 0 ? 1 : 0;
                                $request_score += ($score > 0 ? $score : 0);

                                $RateCriteriaResult->status_index = $status_index; 

                                if (!$RateCriteriaResult->save()) {
                                    print_r($RateCriteriaResult->errors);
                                    die;
                                } 

                                echo "RateCriteriaResult {$msgCrit} id = {$RateCriteriaResult->id} Result: {$score}\n";


                            }
                        }
                    }
                    $RateRequest->score = $request_score;
                    $RateRequest->save();
                }
                echo "$i/$count Site updated SiteID: {$Site->id}\n";
                $i++;
            }
        }
    }


    // получает из json property по имени
    public function getPropertyByName($json,$name,$val_name = 'array') {
        foreach ($json as $val) {
            foreach ($val['property'] as $value) {
                if ($value['name'] == $name) {
                    if ($val_name == 'array') {
                        return implode(',',$value['value']);
                    } else {
                        return $value['value'][$val_name];    
                    }
                    
                }
            }
        }
        return '';
    }

    public function changeSequence($tablename, $sec_name, $fieldname = 'id') {
        $query = new Query;
        $query->from($tablename);            
        $max = $query->max($fieldname);

        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("
            SELECT setval('$sec_name', $max);
        ");
        $command->execute();
        echo "Sequence changed: $sec_name = $max\n";
    }
}
