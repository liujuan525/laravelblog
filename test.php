<!--调试短信接口-->
php artisan tinker


$sms = app('easysms');

<!--在云片网注册的时候没有填写开发者信息，所以content里面没有签名，默认的签名为云片网-->
try {
$sms->send(18401479871, [
'content'  => '您的验证码是1234。如非本人操作，请忽略本短信',
]);
} catch (\GuzzleHttp\Exception\ClientException $exception) {
$response = $exception->getResponse();
$result = json_decode($response->getBody()->getContents(), true);
dd($result);
}