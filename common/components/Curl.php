<?php

namespace common\components;

use Yii;

class Curl {

    public $timeout = 3;
    public $info = null;

    public function init(){

    }

    /**
     * Get запрос
     */
    public function get($url, $request = [], $customOpts = []){

        $address = $url . ( $request ? '?' . http_build_query($request) : '');
        return $this->download($address, $customOpts);
    }

    /**
     * Post запрос
     */
    public function post($url, $request = [], $customOpts = []){

        $ch = curl_init();

        $defOpts = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => is_array($request) ? http_build_query($request) : $request,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ];

        $opts = $customOpts + $defOpts;
        curl_setopt_array($ch, $opts);

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    public function postJson($url, $request = [], $customOpts = []){

        $defOpts =  [
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($request)
            ]
        ];

        $opts = $customOpts + $defOpts;
        return $this->post($url, $request, $opts);
    }

    public function putJson($url, $request = [], $customOpts = []){

        $defOpts =  [
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($request)
            ]
        ];

        $opts = $customOpts + $defOpts;
        return $this->put($url, $request, $opts);
    }

    public function put($url, $request = [], $customOpts = []){

        $ch = curl_init();

        $defOpts = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => is_array($request) ? http_build_query($request) : $request,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ];

        $opts = $customOpts + $defOpts;
        curl_setopt_array($ch, $opts);

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    /**
     * Загружаем данные
     */
    public function download($url, $customOpts = []){

        $ch = curl_init();

        $defOpts = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_TIMEOUT => $this->timeout,
        ];

        $opts = $customOpts + $defOpts;

        curl_setopt_array($ch, $opts);

        $data = @curl_exec($ch);

        if( !$data ){
            return null;
        }

        $this->info = curl_getinfo($ch);

        curl_close($ch);

        return $data;
    }

    public function getInfo(){
        return $this->info;
    }

    public function cacheDownload($url, $time = 900){

        // Извлекаем из кеша
        $cache = Yii::$app->cache->get($url);

        if( !empty($cache) ){
            Yii::info('Cache: ' . $url, '\common\components\Curl::cacheDownload');
            Yii::info('Cache: ' . $cache, '\common\components\Curl::cacheDownload');
            return $cache;
        }

        $response = $this->download($url);

        Yii::info('Download code '.  $this->info['http_code'] .': ' . $url, '\common\components\Curl::cacheDownload');
        Yii::info('Download: ' . $response, '\common\components\Curl::cacheDownload');

        // Сохраняем кеш только если ответ корректный
        if( $this->info['http_code'] == 200 ){
            Yii::$app->cache->set($url, $response, $time);
        }

        return $response;
    }

}
