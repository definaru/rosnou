<?php
namespace frontend\rating\managers;

use mPDF;
use common\models\Site;
use common\models\RatePeriod;
use common\models\RateRequest;

class DiplomManager
{

    public function getDiplom(Site $Site, RatePeriod $Period, RateRequest $Request, $MaxScore, $ScoreData) {

    	$diplom = [];

        // формирование pdf
        $CURRENT_WORKING_DIR = \Yii::getAlias('@webroot');

        $folder = $CURRENT_WORKING_DIR .'/images/diploms/' . $Period->diplom_folder . '/pdf/';
        $filename = $Site->id.'.pdf';

        if ( !is_dir($folder) ){
            mkdir($folder, 0777, true);
        }

		if (is_file($folder . $filename)) {
	        return array(
				'file' => $folder . $filename,
				'name' => $filename
			);
		}

        $class = $ScoreData['diplom_class'];

		$html = '<body>
            <div class="layout_diplom '.$class.'">
                <div class="layout_diplom_top">
                    <h2>Общероссийский рейтинг<br/>образовательных сайтов</h2>
                </div>
                <div class="layout_diplom_content">
                    <div class="layout_diplom_title">'.$ScoreData['title'].'</div>
                    <a href="" class="layout_diplom_link">'.$Site->domain.'</a>
                    <div class="layout_diplom_name">'.$Site->title.' <br/> <small>'.$Site->location.'</small></div>
                    <div class="layout_diplom_rez">Итоговый балл <span>'.$Request->score.'</span> из <span>'.$MaxScore.'</span><br/>'.$Period->title.'</div>
                    <div class="layout_diplom_rector">Ректор Российского нового университета&nbsp;
                    <span>Зернов В.А.</span></div>
                </div>
                <div class="layout_diplom_bottom">
                    <div class="layout_diplom_logo1"></div>
                    <div class="layout_diplom_logo2"></div>
                </div>
            </div>
        </body>';

        $mpdf = new mPDF('utf-8', 'A4', '8', '', 0, 0, 0, 0, 10, 10);

        $stylesheet2 = file_get_contents($CURRENT_WORKING_DIR . '/images/diplom_templates/css/style.css');

        $mpdf->WriteHTML($stylesheet2, 1);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html, 0);

        $mpdf->Output($folder . $filename, 'F');

        $diplom['file'] = $folder . $site_id . '.pdf';
        $diplom['name'] = $site_id . '.pdf';

        return array(
			'file' => $folder . $filename,
			'name' => $filename
		);
    }
}
