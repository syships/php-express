# php-express
This is a personal plugin for study.
It is strongly recomended thant you not use it. 
This plugin is unstable.
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
$config = [
            'zop'=>[
                'express'=>'zop',
                'app_id'=>'app_id',
                'app_secret'=>'app_secret'
            ]
        ];
        //拦截状态查询接口
        $zopApi = new ZopApplication($config['zop']);
        $action = 'thirdcenter.queryInterceptAndReturnStatus';
        $data = ['billCode'=>73100057041226];
        $response = $zopApi->call($action, $data);
        return ($response);

```
