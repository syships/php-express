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
php composer.phar require --prefer-dist syships/php-express "*"
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
$companyName = '中通';
$config = [
    'app_id'=>'app_id',
    'app_secret'=>'app_secret',
    'server_url'=>'http://japi-test.zto.com/',
];
$data = [
    'third_biz_no'=>'',//外部业务单号
    'waybill_code'=>'',//运单号
    'desc'=>''//取消描述
];
$logisticApp = new LogisticApplication($companyName,$config);
//拦截取消请求
if(!$logisticApp->interceptCancel($data)){
    throw new Exception($logisticApp->getFirstError());
}

return $logisticApp->getSuccessData();

```
