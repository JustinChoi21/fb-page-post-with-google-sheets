<?php
namespace Src;

use Src\Facebook\FacebookApi;
use Src\Google\GoogleSpreadsheets;

/**
 * User: heest
 * Date: 2019-01-29
 * Time: 오후 8:41
 */

class AutoPost {

    public function start($spreadsheetId, $range, $page_id) {

        ############# get data from google spreadsheets
        $GoogleSpreadsheets = new GoogleSpreadsheets();
        $facebookPostData = $GoogleSpreadsheets->getFaceBookPostData($spreadsheetId, $range);
        //var_dump($facebookPostData);

        // post to facebook page format
        $status = $facebookPostData->type;
        $anchor_url = $facebookPostData->anchor_url;
        $title = $facebookPostData->title;
        $comment = $facebookPostData->comment;
        $post_date = $facebookPostData->post_date;
        $row_index = $facebookPostData->row_index + 6; // 7행부터 시작

        ############## Facebook Post to Page
        $FacebookApi = new FacebookApi();
        $post_id = $FacebookApi->postToPage($page_id, $comment, $anchor_url);

        ############## Google spreadsheets 에서 날짜 표시
        if($post_id) { // $post_id
            $cell_range = "usa!E" . $row_index;
            $GoogleSpreadsheets->updatePostDate($spreadsheetId, $cell_range);
        } else {
            Log::printT("post_id is false.. maybe facebook postToPage() fail..");
        }

    }
}