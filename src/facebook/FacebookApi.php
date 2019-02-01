<?php
namespace Src\Facebook;

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Src\Log;

/**
 * Created by PhpStorm.
 * User: heest
 * Date: 2019-01-30
 * Time: 오후 6:04
 */

class FacebookApi {

    private $fb;
    private $user_access_token;
    private $app_id;
    private $app_secret;

    public function __construct() {

        $config = parse_ini_file("facebook_auth.ini");

        // facebook_login.php 에서 받은 토큰 붙여넣기
        $this->user_access_token = $config['user_access_token'];
        $this->app_id = $config['app_id'];
        $this->app_secret = $config['app_secret'];

        try {
            $fb = new Facebook([
                'app_id' => $this->app_id,
                'app_secret' => $this->app_secret,
                'default_graph_version' => 'v3.2',
                'default_access_token' => $this->user_access_token
            ]);
        } catch (FacebookSDKException $e) {
            Log::printT("Facebook SDK returned an error : " . $e->getMessage());
            exit;
        }

        $this->fb = $fb;
    }

    public function getPageList()
    {
        // graph api 에 요청해서 graphNode Facebook\GraphNodes\GraphNode를 받으면 Facebook\GraphNodes\GraphEdge를 사용할 수 있다.
        // https://github.com/facebook/php-graph-sdk/blob/5.x/docs/reference/GraphEdge.md 참조
        // https://github.com/facebook/php-graph-sdk/blob/5.x/docs/reference/GraphNode.md 참조
        // response : FacebookResponse 클래스

        $page_list_graph_edge = array();

        try {
            $response = $this->fb->get(
                'me/accounts?fields=access_token, name, id'
            );

            $page_list_graph_edge = $response->getGraphEdge();

        } catch (FacebookSDKException $e) {
            Log::printT("Facebook SDK returned an error : " . $e->getMessage());
        }

        return $page_list_graph_edge;
    }

    public function getPageAccessToken($page_id) {
        $page_access_token = "";

        $page_list_graph_edge = $this->getPageList();

        foreach($page_list_graph_edge as $page_list_graph_node) {
            if($page_id == $page_list_graph_node->getField('id')) {
                $page_access_token = $page_list_graph_node->getField('access_token');
            }
        }

        return $page_access_token;
    }


    public function getPageFeeds($page_id) {

        $page_access_token = $this->getPageAccessToken($page_id);

        if($this->fb) {
            try {
                $response = $this->fb->get(
                    '/'.$page_id.'/feed',
                    $page_access_token
                );

                $graphEdge = $response->getGraphEdge();
//                foreach ($graphEdge as $grapNode) {
//                    echo $grapNode->getField('message') . PHP_EOL;
//                }

            } catch (FacebookResponseException $e) {
                Log::printT("Graph returned an error : " . $e->getMessage());
                exit;

            } catch (FacebookSDKException $e) {
                Log::printT("Facebook SDK returned an error : " . $e->getMessage());
                exit;
            }

        } else {
            Log::printT("There is no fb..");
            exit;
        }

        return $graphEdge;
    }

    public function postToPage($page_id, $comment, $anchor_url) {

        $page_access_token = $this->getPageAccessToken($page_id);

        if($this->fb) {
            try {
                $response = $this->fb->post(
                    '/'.$page_id.'/feed',
                    array (
                        'message' => $comment,
                        'link' => $anchor_url
                    ),
                    $page_access_token
                );

                $getGraphNode = $response->getGraphNode();
                $post_id =  $getGraphNode->getField('id');

            } catch (FacebookResponseException $e) {
                Log::printT("Graph returned an error : " . $e->getMessage());
                exit;

            } catch (FacebookSDKException $e) {
                Log::printT("Facebook SDK returned an error : " . $e->getMessage());
                exit;
            }

        } else {
            Log::printT("There is no fb..");
            exit;
        }

        return $post_id;
    }
}

