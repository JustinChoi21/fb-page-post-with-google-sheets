<?php
namespace Src;

/**
 * User: heest
 * Date: 2019-01-29
 * Time: 오후 8:38
 */

class Schedule {

    public function checkSchedule($action_name) {
        // 스케쥴 시간을 체크하고 doAction()을 호출
        $doNow = false;

        switch ($action_name) {
            case "post_by_schedule" :

                // todo : schedule check process

                break;
            default :
                Log::printT("checkSchedule action_name is wrong..");
        }

        return $doNow;
    }

    public function doAction($action_name, $spreadsheetId, $range, $page_id) {

        $AutoPost = new AutoPost();

        // check and action
        switch($action_name) {

            case "post_immediately" :
                $AutoPost->start($spreadsheetId, $range, $page_id);
                break;

            case "post_by_schedule" :
                if($this->checkSchedule($action_name)) {
                    $AutoPost->start($spreadsheetId, $range, $page_id);
                }
                break;

            default :
                Log::printT("please, check action name..");
        }
    }
}