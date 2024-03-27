<?php

namespace common\widgets\elfinder;

use common\models\ElfinderLog;
use common\components\Helper;

/**
 * Simple logger function.
 * Demonstrate how to work with elFinder event api.
 **/
class elFinderLogger {

    /**
     * Log file path
     *
     * @var string
     **/
    //protected $file = '';
    protected $holder;

    /**
     * constructor
     * @resource $holder - database connection
     * @return void
     **/
    public function __construct($holder) {
        $this->holder = $holder;
    }

    /**
     * Create log record
     *
     * @param  string   $cmd       command name
     * @param  array    $result    command result
     * @param  array    $args      command arguments from client
     * @param  elFinder $elfinder  elFinder instance
     * @return void|true
     * @author Dmitry (dio) Levashov
     **/
    public function log($cmd, $result, $args, $elfinder) {

        $log = '';//$cmd.' ['.date('d.m H:s')."]\n";

        if (!empty($result['error'])) {
            $log .= "\tERROR: ".implode(' ', $result['error'])."\n";
        }

        if (!empty($result['warning'])) {
            $log .= "\tWARNING: ".implode(' ', $result['warning'])."\n";
        }

        if (!empty($result['removed'])) {
            foreach ($result['removed'] as $file) {
                // removed file contain additional field "realpath"
                $log .= "\tREMOVED: ".$file['realpath']."\n";
            }
        }

        if (!empty($result['added'])) {
            foreach ($result['added'] as $file) {
                $log .= "\tADDED: ".$elfinder->realpath($file['hash'])."\n";
            }
        }

        if (!empty($result['changed'])) {
            foreach ($result['changed'] as $file) {
                $log .= "\tCHANGED: ".$elfinder->realpath($file['hash'])."\n";
            }
        }

        $this->write($log);
    }

    /**
     * Write log into file
     *
     * @param  string  $log  log record
     * @return void
     **/
    protected function write($msg) {

        $site = Helper::loadSite();

        $Log = new ElfinderLog();
        $Log->site_id = $site->id;
        $Log->type_index = 0;
        $Log->created_datetime = date('Y-m-d H:i:s');
        $Log->body = $msg;
        $Log->user_id = \Yii::$app->user->id;
        $Log->save();
    }
}
