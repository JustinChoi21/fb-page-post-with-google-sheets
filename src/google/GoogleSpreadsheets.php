<?php
namespace Src\Google;

use Google_Client;
use Google_Service_Sheets;
use Google_Service_Sheets_ValueRange;
use Src\Log;

class GoogleSpreadsheets {

    private $client;

    public function getFaceBookPostData($spreadsheetId, $range) {

        $facebookPostData = (object) array();
        $facebookPostData->type = "";
        $facebookPostData->anchor_url = "";
        $facebookPostData->title = "";
        $facebookPostData->comment = "";
        $facebookPostData->post_date = "";
        $facebookPostData->row_index = 0;
        $row_index = 0;

        $values = $this->getValues($spreadsheetId, $range);
        if(empty($values)) {
            Log::printT("No data found.");

        } else {

            // 구글 스프레드시트의 전체 row 를 가져와서 status 확인 후 최초로 status가 0인 데이터 선정
            foreach($values as $row) {

                $row_index++;

                if(count($row) > 4) {
                    $post_date = $row[4];

                    if($post_date == "N") {
                        $facebookPostData->type = $row[0];
                        $facebookPostData->anchor_url = $row[1];
                        $facebookPostData->title = $row[2];
                        $facebookPostData->comment = $row[3];
                        $facebookPostData->post_date = $row[4];
                        $facebookPostData->row_index = $row_index;
                        break;
                    }
                }
            }
        }

        return $facebookPostData;
    }

    public function getValues($spreadsheetId, $range) {

        // check and get auth
        if(!$this->client) {
            $this->getAuth();
        }

        $service = new Google_Service_Sheets($this->client);
        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();

        return $values;
    }

    private function getAuth() {
        if(!$this->client) {
            $this->client = $this->getClient();

        } else {
            Log::printT("already have auth..");
        }
    }

    private function getClient() {
        $client = new Google_Client();
        $client->setApplicationName('Google Sheets API PHP');
        $client->setScopes(Google_Service_Sheets::SPREADSHEETS);
        $client->setAuthConfig(__DIR__ . '/credentials.json');
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        // Load previously authorized token from a file, if it exists.
        // The file token.json stores the user's access and refresh tokens, and is
        // created automatically when the authorization flow completes for the first
        // time.
        $tokenPath = __DIR__.'/token.json';
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }

        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                // Request authorization from the user.
                $authUrl = $client->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $authUrl);
                print 'Enter verification code: ';
                $authCode = trim(fgets(STDIN));

                // Exchange authorization code for an access token.
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);

                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new Exception(join(', ', $accessToken));
                }
            }
            // Save the token to a file.
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }
        return $client;
    }

    public function updatePostDate($spreadsheetId, $cell_range) {

        // check and get auth
        if(!$this->client) {
            $this->getAuth();
        }

        $service = new Google_Service_Sheets($this->client);

        // [START sheets_update_values]
        $now = date("Y-m-d H:i:s");

        $values = [
            [
                $now
            ],
            // Additional rows ...
        ];

        $body = new Google_Service_Sheets_ValueRange([
            'range' => $cell_range,
            'majorDimension' => 'ROWS',
            'values' => $values
        ]);

        $result = $service->spreadsheets_values->update(
            $spreadsheetId,
            $cell_range,
            $body,
            ['valueInputOption' => "RAW"]
        );
        Log::printT("%d cells updated. " . $result->getUpdatedCells());
        // [END sheets_update_values]
        return $result;
    }
}