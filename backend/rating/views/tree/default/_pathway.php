<?php $this->params['breadcrumbs'][] = ['label' => 'Структура сайта', 'url' => ['/tree/default/index']];

if ($parents) {
    foreach ($parents as $key => $parent) {
        $this->params['breadcrumbs'][] = [
            'label' => $parent['title'],
            'url' => ['/tree/default/index?parent_id=' . $parent['id']]
        ];
    }
}

$this->params['breadcrumbs'][] = $title;
