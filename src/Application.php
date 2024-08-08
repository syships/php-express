<?php

namespace syships\express;

class Application {
    private $baseUrl;
    private $appid;
    private $appsecret;
    private $tag;

    public function __construct($config) {
        if($config['debug']){
            $this->baseUrl = 'http://japi-test.zto.com/';
        }else{
            $this->baseUrl = 'http://japi-test.zto.com/';
        }
        $this->appid = $config['app_id'];
        $this->appsecret = $config['app_secret'];
    }

    public function call($action, $data) {
        $properties = new ZopProperties($this->appid, $this->appsecret);
        $client = new ZopClient($properties);
        $request = new ZopRequest();
        $request->setUrl($this->baseUrl.$action);
        $request->setData(json_encode($data));
        return $client->execute($request);
    }
}
