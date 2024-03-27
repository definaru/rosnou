<?php

/**
 * elFinder driver for yii2 filesystem.
 *
 **/
use common\components\Helper;
use common\managers\EipManager;
use common\managers\DiskUsageManager;


class elFinderVolumeYii extends elFinderVolumeLocalFileSystem {

    /**
     * Driver id
     * Must be started from letter and contains [a-z0-9]
     * Used as part of volume id
     *
     * @var string
     **/
    protected $driverId = 'l';

    /**
     * Required to count total archive files size
     *
     * @var int
     **/
    protected $archiveSize = 0;

    /**
     * Canonicalized absolute pathname of $root
     *
     * @var strung
     */
    protected $aroot;

    /**
     * Constructor
     * Extend options with required fields
     * https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options
     * @return void
     **/
    public function __construct() {

        $this->options['sizemode'] = 1;
        $this->options['uploadOverwrite'] = false;
        $this->options['alias']    = '';              // alias to replace root dir name
        $this->options['dirMode']  = 0755;            // new dirs mode
        $this->options['fileMode'] = 0644;            // new files mode
        $this->options['quarantine'] = '.quarantine';  // quarantine folder name - required to check archive (must be hidden)
        $this->options['maxArcFilesSize'] = 0;        // max allowed archive files size (0 - no limit)
        $this->options['icon']     = (defined('ELFINDER_IMG_PARENT_URL')? (rtrim(ELFINDER_IMG_PARENT_URL, '/').'/') : '').'img/volume_icon_local.png';


    }

    /*********************************************************************/
    /*                        INIT AND CONFIGURE                         */
    /*********************************************************************/

    /**
     * Configure after successfull mount.
     *
     * @return void
     * @author Dmitry (dio) Levashov
     **/
    //protected function configure() {}

    /*********************************************************************/
    /*                               FS API                              */
    /*********************************************************************/

    /*********************** paths/urls *************************/

    /**
     * Return parent directory path
     *
     * @param  string  $path  file path
     * @return string
     * @author Dmitry (dio) Levashov
     **/
    protected function _dirname($path) {
        return dirname($path);
    }

    /**
     * Return file name
     *
     * @param  string  $path  file path
     * @return string
     * @author Dmitry (dio) Levashov
     **/
    protected function _basename($path) {
        return basename($path);
    }

    /**
     * Join dir name and file name and retur full path
     *
     * @param  string  $dir
     * @param  string  $name
     * @return string
     * @author Dmitry (dio) Levashov
     **/
    protected function _joinPath($dir, $name) {
        return $dir.DIRECTORY_SEPARATOR.$name;
    }

    /**
     * Return normalized path, this works the same as os.path.normpath() in Python
     *
     * @param  string  $path  path
     * @return string
     * @author Troex Nevelin
     **/
    //protected function _normpath($path) {}

    /**
     * Return file path related to root dir
     *
     * @param  string  $path  file path
     * @return string
     * @author Dmitry (dio) Levashov
     **/
    //protected function _relpath($path) {
    //    return $path == $this->root ? '' : substr($path, strlen($this->root)+1);
    //}

    /**
     * Convert path related to root dir into real path
     *
     * @param  string  $path  file path
     * @return string
     * @author Dmitry (dio) Levashov
     **/
    //protected function _abspath($path) {
    //    return $path == DIRECTORY_SEPARATOR ? $this->root : $this->root.DIRECTORY_SEPARATOR.$path;
    //}

    /**
     * Return fake path started from root dir
     *
     * @param  string  $path  file path
     * @return string
     * @author Dmitry (dio) Levashov
     **/
    //protected function _path($path) {
    //    return $this->rootName.($path == $this->root ? '' : $this->separator.$this->_relpath($path));
    //}

    /**
     * Return true if $path is children of $parent
     *
     * @param  string  $path    path to check
     * @param  string  $parent  parent path
     * @return bool
     * @author Dmitry (dio) Levashov
     **/
    //protected function _inpath($path, $parent) {}



    /***************** file stat ********************/

    /**
     * Return stat for given path.
     * Stat contains following fields:
     * - (int)    size    file size in b. required
     * - (int)    ts      file modification time in unix time. required
     * - (string) mime    mimetype. required for folders, others - optionally
     * - (bool)   read    read permissions. required
     * - (bool)   write   write permissions. required
     * - (bool)   locked  is object locked. optionally
     * - (bool)   hidden  is object hidden. optionally
     * - (string) alias   for symlinks - link target path relative to root path. optionally
     * - (string) target  for symlinks - link target path. optionally
     *
     * If file does not exists - returns empty array or false.
     *
     * @param  string  $path    file path
     * @return array|false
     * @author Dmitry (dio) Levashov
     **/
    //protected function _stat($path) {}


    /**
     * Return true if path is dir and has at least one childs directory
     *
     * @param  string  $path  dir path
     * @return bool
     * @author Dmitry (dio) Levashov
     **/
    //protected function _subdirs($path) {}

    /**
     * Return object width and height
     * Usualy used for images, but can be realize for video etc...
     *
     * @param  string  $path  file path
     * @param  string  $mime  file mime type
     * @return string
     * @author Dmitry (dio) Levashov
     **/
    //protected function _dimensions($path, $mime) {}
    /******************** file/dir content *********************/

    /**
     * Return symlink target file
     *
     * @param  string  $path  link path
     * @return string
     * @author Dmitry (dio) Levashov
     **/
    //protected function readlink($path) {}

    /**
     * Return files list in directory.
     *
     * @param  string  $path  dir path
     * @return array
     * @author Dmitry (dio) Levashov
     **/
    //protected function _scandir($path) {}

    /**
     * Open file and return file pointer
     *
     * @param  string  $path  file path
     * @param  bool    $write open file for writing
     * @return resource|false
     * @author Dmitry (dio) Levashov
     **/
    //protected function _fopen($path, $mode='rb') {}

    /**
     * Remove file
     *
     * @param  string  $path  file path
     * @return bool
     * @author Dmitry (dio) Levashov
     **/
      protected function _unlink($path) {

          $EipManager = new EipManager(null, null, null, null);
          $EipManager->deleteThumbs($path);

          return @unlink($path);
      }

    /**
     * Remove dir
     *
     * @param  string  $path  dir path
     * @return bool
     * @author Dmitry (dio) Levashov
     **/
    //protected function _rmdir($path) {
    //    return @rmdir($path);
    //}

    /**
     * Create new file and write into it from file pointer.
     * Return new file path or false on error.
     *
     * @param  resource  $fp   file pointer
     * @param  string    $dir  target dir path
     * @param  string    $name file name
     * @param  array     $stat file stat (required by some virtual fs)
     * @return bool|string
     * @author Dmitry (dio) Levashov
     **/
    protected function _save($fp, $dir, $name, $stat) {

        $path = $dir.'/'.$name;

        // костыль, нужно научиться передавать корректно параметры из виджета ElFinder
        //$dirName = basename($dir);

        // В персональных в дизайне и в официальных включаем режим 2000x2000
        if( strpos($path, '/background/') !== false 
            || strpos($path, '/backgrounds/') !== false
            || strpos($path, '/settings/') !== false ){
            $this->options['sizemode'] = 2;
        }

        $size = $this->options['sizemode'] == 2 ? "2000x2000" : "1000x1000";

        if (@file_put_contents($path, $fp, LOCK_EX) === false) {
            return false;
        }

        // optimization
        $parts = explode('.', $path);
        $ext = strtolower(end($parts));

        if( $ext == 'jpg' || $ext == 'jpeg' ){
            exec( 'mogrify -resize "' . $size . '>" -quality 90 ' . $path );
            Helper::fixOrient($path);
        }

        if( $ext == 'png' ){
            exec( 'mogrify -resize "' . $size . '>" -format png ' . $path );
        }

        if( $ext == 'gif' ){
            exec( 'mogrify -resize "' . $size . '>" -format gif ' . $path );
        }

        if( $ext == 'bmp' ){
            exec( 'mogrify -resize "' . $size . '>" -format bmp ' . $path );
        }

        // После загрузки в систему тумбы картинки очищаются
        $EipManager = new EipManager(null, null, null, null);
        $EipManager->deleteThumbs($path);

        @chmod($path, $this->options['fileMode']);

        return $path;
    }

    /**
     * Remove file/dir
     *
     * @param  string  $hash  file hash
     * @return bool
     * @author Dmitry (dio) Levashov
     **/

    /*
    public function rm($hash) {

        $info = $this->file($hash);
        $path = $this->decode($hash);

        $Site = Yii::$app->factory->site;
        $DiskUsageManager = new DiskUsageManager($Site);

        if( $DiskUsageManager->hasDatabaseRecord($path) ){
            return $this->setError(
                'Файл ' . $info['name'] . ' не может быть удален, ' . 
                'поскольку используется на сайте, ' . 
                'сначала запись с файлом с сайта'
            );            
        }

        //return $this->setError('Ошибка удаления фай');
    
        return parent::rm($hash);
    }*/

} // END class
