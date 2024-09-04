# php-logistic
This is a personal plugin for study.
It is strongly recomended that you DO NOT use it. 
This plugin is UNSTABLE.
======================
It is the logistic plugin for php
======================

Installation
------------

The preferred way to install this extension is through [composer](https://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist syships/php-logistic "*"
```

or add

```
"syships/php-logistic": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :


```
require_once __DIR__.'/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Response;
use syships\logistic\LogisticApplication;

$companyName = '中通';
$config = [
    'app_id'=>'4fcf58d7f359f05b643a7',
    'app_secret'=>'78bdd4970c57b71b82c62072f700b654',
    'server_url'=>'http://japi-test.zto.com/',
];
$data = [
    'waybill_code'=>"73100135377958",//运单号
];
$logisticApp = new LogisticApplication($companyName,$config);
//拦截取消请求
if($logisticApp->track($data)){
    $response = new Response(json_encode($logisticApp->getSuccessData()),200,['Content-Type'=>'application/json']);
    $response->send();
}else{
    $response = new Response($logisticApp->getFirstError(),400,['Content-Type'=>'application/json']);
    $response->send();
}

```
