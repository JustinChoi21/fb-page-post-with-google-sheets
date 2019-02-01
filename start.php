<?php

use Src\Log;
use Src\Schedule;

/**
 * Created by PhpStorm.
 * User: heest
 * Date: 2019-01-29
 * Time: 오후 8:24
 */
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';

} else {
    Log::printT("oops! autoload failed!");
}

$startTime = Log::printStartTime();

if(count($argv) == 5) {
    $Schedule = new Schedule();
    $Schedule->doAction($argv[1], $argv[2], $argv[3], $argv[4]);

} else {
    Log::printT("start.php need action name, spreadsheetId, range");
}


Log::printEndTime($startTime);





