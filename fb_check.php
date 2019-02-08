<?php
/**
 * User: heest
 * Date: 2019-01-29
 * Time: 오후 8:24
 */

use Src\Log;
use Src\Schedule;


if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';

} else {
    Log::printT("oops! autoload failed!");
}

$startTime = Log::printStartTime();

$Schedule = new Schedule();
$Schedule->facebookCheck($argv);



Log::printEndTime($startTime);





