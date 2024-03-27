<?php
namespace common\components;

use Yii;
use common\components\Helper;

class ElfinderUtils
{
    public $site;

    function init(){

    }

    /**
     * Метод добавляет в url параметры
     * $file_path - хеш с именем файла
     * #elf_... - хеш с именем директории
     * в редактор вставляется url /elfinder/xmanager?....&file_path=[путь до файла который нужно открыть в elfinder]
     * в результате вызова buildUrl изначальный url преобразуется таким образом что
     * открытый браузер elinder показывает директорию файл в которой выбран и выделяет сам файл
     */
    public static function buildUrl($request){

        $rootPath = $request->get('path');
        $filePath = $request->get('file_path');

        if( strpos( $filePath, '?' ) ){
            $filePath = substr( $filePath, 0, strpos( $filePath, '?' ) );
        }

        // Сделано чтобы filepath не реагировал на значения типа
        // none, undefined
        if( strpos( $filePath, '.' ) === false ){
            $filePath = false;
        }

        // путь file_path задается пустым когда у кртинки указан класс eip-noset
        // Clean path (.thumbs)
        //if( in_array($filePath, ['/image/spacer.gif', '/common/img/spacer.gif'] ) ){
        //    $filePath = false;
        //}

        //$removeParts = ['/.thumbs'];
        //$rootPath = str_replace($removeParts, '', $rootPath);
        //$filePath = str_replace($removeParts, '', $filePath);
        //

        $parts['filter'] = $request->get('filter');
        $parts['callback'] = $request->get('callback');
        $parts['multiple'] = $request->get('multiple');
        $parts['lang'] = $request->get('lang');
        $parts['sizemode'] = $request->get('sizemode');
        $parts['path'] = $rootPath;

        $hash = '';

        if( $filePath ){

            // Файловый хеш
            $parts['file_hash'] = self::encode('l1_', $rootPath, $filePath);

            // folder hash
            $hash = '#' . self::encode('elf_l1_', $rootPath, dirname($filePath) );
        }

        $url = '/admin/elfinder/manager?' . http_build_query($parts) . $hash;
        return $url;
    }

    /**
     * Encode path into hash
     *
     * @param  string  file path
     * @return string
     * @author Dmitry (dio) Levashov
     * @author Troex Nevelin
     **/
    static protected function encode($id, $root, $path) {

        if ($path !== '') {
            // cut ROOT from $path for security reason, even if hacker decodes the path he will not know the root
            $delimParts = explode('/', trim($root, '/'));
            $delimeter = end($delimParts);
            $parts = explode("/{$delimeter}/", $path);
            $localpath = end($parts);
            $p = $localpath;
            $p = trim($p, '.');
            //$p = 'foto';//$path;//$this->relpathCE($path);

            // if reqesting root dir $path will be empty, then assign '/' as we cannot leave it blank for crypt
            if ($p === ''){
              $p = DIRECTORY_SEPARATOR;
            }

            // TODO crypt path and return hash
            $hash = self::crypt($p);
            // hash is used as id in HTML that means it must contain vaild chars
            // make base64 html safe and append prefix in begining
            $hash = strtr(base64_encode($hash), '+/=', '-_.');
            // remove dots '.' at the end, before it was '=' in base64
            $hash = rtrim($hash, '.');
            // append volume id to make hash unique
            return $id.$hash;
        }
    }

   static protected function crypt($path) {
        return $path;
    }

}

