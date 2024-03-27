<?php

namespace frontend\rating\managers;

use frontend\rating\forms\SearchForm;

class SearchManager
{
	public $orderBy = 'id';

    public function search(SearchForm $form)
    {
    	$queryNews = (new \yii\db\Query())
    	->select("id, title, preview as content, slug as path, ('news') as type")
    	->from('tbl_news')
    	->where(['is_published' => 1])
    	->andWhere(['or', ['like', 'title', '%'.$form->query.'%', false],['like', 'content', '%'.$form->query.'%', false]]);

		$querySection = (new \yii\db\Query())
		    ->select("id, title, body as content, route as path, ('section') as type")
		    ->from('tbl_section')
		    ->andWhere(['or', ['like', 'title', '%'.$form->query.'%', false],['like', 'body', '%'.$form->query.'%', false]]);;

		
		$query = (new \yii\db\Query)
        ->select('*')
        ->from([
            $queryNews->union($querySection),
        ]);

    	$itemCount = $query->count();

        // Пагинация
        $pages = new \yii\data\Pagination([
            'totalCount' => $itemCount,
            //'defaultPageSize'=> 2,
            //'pageSize'=> 2 ,

        ]);

        // Список
        $query->offset($pages->offset)->limit($pages->limit);
        $query->orderBy($this->orderBy);

        $list = $query->all();

        $list = $this->createLinks($list);


        return [
            'query' => $query,
            'list' => $list,
            'pages' => $pages,
            'count' => $itemCount,
        ];
    }

    private function createLinks($list)
    {
    	$result = [];
    	foreach ($list as $item) {
    		if ($item['type'] == 'news') {
    			$item['path'] = \yii\helpers\Url::toRoute(['/news/view', 'slug' => $item['path']]);

    		}
            if ($item['type'] == 'section') {
                // обрезка текста
                $item['content'] = $this->firstParagraph($item['content']);
                $item['path'] .= '/';
            }

    		array_push($result, $item);
    	}
    	return $result;
    }

    /** * Извлекаем из указанного текста первый параграф */ 
    private function firstParagraph($text, $limit = 100)
    { 
        $pPos = mb_strpos($text, '<p>'); 
        $text = $pPos === false ? $text : mb_substr($text, $pPos);

        $text = str_replace('<p></p>', '', $text); 
        $parts = explode('</p>', $text); 
        $part = array_shift($parts); 
        $part = strip_tags($part); 
        $part = mb_substr($part, 0, $limit); 
        $dotPos = mb_strrpos($part, '.'); 
        $part = $dotPos === false ? $part : mb_substr($part, 0, $dotPos+1); 
        return $part; 
    }
}