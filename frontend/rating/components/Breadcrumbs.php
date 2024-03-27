<?php

namespace frontend\rating\components;

use common\managers\SectionManager;
use Yii;

class Breadcrumbs extends \yii\widgets\Breadcrumbs
{
    public $itemTemplate = "<li>{link}</li>\n";
    public $tag = 'ol';

   public function init()
   {
       parent::init();

       $this->homeLink = $this->homeLink();
       $this->links = $this->sectionLinks();
   }

    /**
     * @return array
     */
    public function sectionLinks()
    {
        $links = [];

        foreach($this->currentSections() as $section) {
            $links[] = ['label' => $section->title, 'url' => [$section->route]];
        }

        if($this->links) {
            $links = array_merge($links, $this->links);
        }
        
        return $this->removeLinkFromLastItem($links);
    }

    /**
     * @return array
     */
    private function homeLink()
    {
        return [
            'label' => '<i class="fa fa-home"></i>',
            'url' => Yii::$app->homeUrl,
            'encode' => false,
        ];
    }

    /**
     * @return mixed
     */
    public function currentSections()
    {
        return \Yii::$container->get(SectionManager::class)->getBreadcrumbs();
    }

    /**
     * @param $links
     * @return mixed
     */
    private function removeLinkFromLastItem($links)
    {
        $index = count($links) - 1;

        if(!isset($links[$index]['url'])) {
            return $links;
        }

        unset($links[$index]['url']);

        return $links;
    }
}