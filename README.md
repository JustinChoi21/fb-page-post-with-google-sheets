# Facebook page poster (using google spreadsheets)
This is a feature that automatically posts on Facebook pages using Google Spreadsheets.

## Installation
1. ```git clone https://github.com/JustinChoi21/fb-page-post-with-google-sheets.git```
2. ```composer install```
3. Google API Authorization (See below)
4. Facebook API Authorization (See below)
5. Facebook App review (See below)

## Exec
```
set XDEBUG_CONFIG="idekey=PHPSTORM" (optional)
php start.php post_immediately 1mnDNHHeJAwDUrlM6n-Hi9xxMkhXTGrikNWKj0db-DAU usa!A7:E 258256297965103
```

## Usage example
Youtube video
Blog URL - Gomcine Blog (Korean)

## Google API Authorization

## Facebook API Authorization
# 페이스북 access_token 받기
http://localhost/auto-post-on-facebook-page-with-google-spreadsheets/src/facebook/facebook_login.php

## Reference
### 1. Google Spreadsheets reference
- [Google APIs Console](https://console.developers.google.com)
- [Google API Client Libraries PHP](https://developers.google.com/api-client-library/php/start/get_started)
- [Google Spreadsheets and PHP - twilio Blog](https://www.twilio.com/blog/2017/03/google-spreadsheets-and-php.html)
- [G Suite PHP Samples](https://github.com/gsuitedevs/php-samples)

### 2. Facebook Graph Api reference
- [Facebook Developer Console](https://developers.facebook.com)
- [Facebook SDK for PHP (v5)](https://github.com/facebook/php-graph-sdk)
- [Getting started with the Facebook SDK for PHP](https://github.com/facebook/php-graph-sdk/blob/5.x/docs/getting_started.md)
- [PHP Facebook SDK 시작하기](https://developers.facebook.com/docs/php/gettingstarted)
- [Graph API page reference](https://developers.facebook.com/docs/graph-api/reference/page/)
- [Page API 시작하기](https://developers.facebook.com/docs/pages/getting-started/#implementation-steps)

### Facebook Access Token reference
- [How to handle access tokens with Facebook SDK v4.0](https://www.sammyk.me/access-token-handling-best-practices-in-facebook-php-sdk-v4)
- [Short-Term Tokens and Long-Term Tokens](https://developers.facebook.com/docs/facebook-login/access-tokens#termtokens)

### Facebook App Review reference
- [Instagram API 연동하기 - Medium Blog](https://medium.com/@alexsung/%EC%9B%B9-2%EB%8B%AC-%EB%82%A8%EC%A7%93-%EA%B1%B8%EB%A6%B0-instagram-%EC%97%B0%EB%8F%99%ED%95%98%EA%B8%B0-4b1e5a125e4a) 
- [How to get you Facebook app approved after the April/May 2018 Facebook app restrictions? - Youtube](https://www.youtube.com/watch?v=ds6PBBsIxeQ)
- [HOW DO I SETUP A FACEBOOK APP, TO ALLOW ME TO POST AUTOMATICALLY TO FACEBOOK (FOR FBOMATIC)?](http://coderevolution.ro/knowledge-base/faq/how-do-i-setup-a-facebook-app-to-allow-me-to-post-automatically-to-facebook/)

## Contributing
1. Fork this repository ```git clone https://github.com/JustinChoi21/fb-page-post-with-google-sheets.git```
2. Create new branch  ```git checkout -b foo/bar```
3. Commit changes  ```git commit -am 'fix: some bugs..```
4. Push to branch ```git push origin foo/bar```
5. Pull Request
