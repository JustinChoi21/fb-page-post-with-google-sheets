<?php
namespace Src;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * User: heest
 * Date: 2019-01-29
 * Time: 오후 8:33
 */

class Log
{
    public static function printT($msg) {
        echo $msg . PHP_EOL;
    }

    public static function printStartTime() {
        $startTime = date("Y-m-d H:i:s");
        echo 'Start ' . $_SERVER['PHP_SELF'] . ' : ' . $startTime . PHP_EOL;

        return $startTime;
    }

    public static function printEndTime($startTime) {
        $endTime = date("Y-m-d H:i:s");
        $diffTime = strtotime($endTime) - strtotime($startTime);
        echo 'End ' . $_SERVER['PHP_SELF'] . ' : ' . $endTime . ' lead time: ' . $diffTime . 'sec' . PHP_EOL;
    }
}



