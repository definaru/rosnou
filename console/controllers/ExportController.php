<?php

namespace console\controllers;

use yii\console\Controller;
use Yii;
use common\models\RateRequest;
use common\models\RatePeriod;
use common\models\Site;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportController extends Controller
{

    public $period = null;

    public function options($actionID) {
        return [
            'period' => 'period',
        ];
    }

    private function getParam($name){

        if( $this->$name === null ){
            echo 'Не указан обязательный параметр --'."{$name}\n";
            die;
        }

        return $this->$name;
    }

    /*
    * Формирует Excel таблицу участников периода по ID
    */
    public function actionResults()
    {
    	$periodID = $this->period;

        $dir = __DIR__ . "/../../../data/export";

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        if ($periodID) {
            $Period = RatePeriod::findOne(['id' => $periodID]);

            if (!$Period) {
                echo "ERROR: Period not exists (id=$periodID).\n";
                die;
            }
            $file = "results-{$Period->slug}.xlsx";
        } else {
            $file = "results.xlsx";
        }

        $querySite = Site::find()->alias('s');

        if ($periodID) {
            $querySite->innerJoinWith('requests as r');
            $querySite->andWhere(['r.period_id' => $periodID]);
        }

        $count = $querySite->count();

        
        $FileName = "$dir/$file";

        echo "Sites count: $count\n";
        echo "FileName: $FileName\n";

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Полное название учебного заведения');
        $sheet->setCellValue('B1', 'Субъект Федерации');
        $sheet->setCellValue('C1', 'Населенный пункт');
        $sheet->setCellValue('D1', 'Категория');
        $sheet->setCellValue('E1', 'Краткое название сайта');
        $sheet->setCellValue('F1', 'Адрес сайта');
        $sheet->setCellValue('G1', 'Федеральный округ');
        $sheet->setCellValue('H1', 'ФИО Директора');
        $sheet->setCellValue('I1', 'e-mail директора');

        $sheet->getColumnDimension('A')->setWidth(100);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(25);
        $sheet->getColumnDimension('F')->setWidth(25);
        $sheet->getColumnDimension('G')->setWidth(25);
        $sheet->getColumnDimension('H')->setWidth(25);
        $sheet->getColumnDimension('I')->setWidth(25);

        $row = 2;
        $i = 1;
        foreach ($querySite->batch(100) as $Sites) {
            foreach ($Sites as $Site) {
                
                echo "($i/$count) Site added: {$Site->domain}\n";
                $sheet->setCellValue('A'.$row, $Site->org_title);
                $sheet->setCellValue('B'.$row, $Site->subject ? $Site->subject->title : '');
                $sheet->setCellValue('C'.$row, $Site->location);
                $sheet->setCellValue('D'.$row, $Site->type->title);
                $sheet->setCellValue('E'.$row, $Site->title);
                $sheet->setCellValue('F'.$row, $Site->domain);
                $sheet->setCellValue('G'.$row, $Site->district ? $Site->district->title : '');
                $sheet->setCellValue('H'.$row, $Site->headname);
                $sheet->setCellValue('I'.$row, $Site->headname_email);
      
                
                $i++;
                $row++;
            }
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($FileName);
    }
}