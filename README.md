# php-logistic
This is a personal plugin for study.
It is strongly recomended thant you DO NOT use it. 
This plugin is UNSTABLE.
======================
It is the express plugin for php
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
"syships/php-express": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Before using the extension,you can add the _ide_helper.php under your application folder.

Once the extension is installed, simply use it in your code by  :


```
$companyName = '中通';
$config = [
    'express'=>'zop',
    'app_id'=>'app_id',
    'app_secret'=>'app_secret'
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
