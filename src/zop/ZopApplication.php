<?php

namespace syships\express\zop;

class ZopApplication {
    private $baseUrl;
    private $appId;
    private $appSecret;

    public function __construct($config) {
        if($config['debug']){
            $this->baseUrl = 'http://japi-test.zto.com/';
        }else{
            $this->baseUrl = 'http://japi-test.zto.com/';
        }
        $this->appId = $config['app_id'];
        $this->appSecret = $config['app_secret'];
    }

    public function call($action, $data) {
        $properties = new ZopProperties($this->appId, $this->appSecret);
        $client = new ZopClient($properties);
        $request = new ZopRequest();
        $request->setUrl($this->baseUrl.$action);
        $request->setData(json_encode($data));
        return $client->execute($request);
    }
}
