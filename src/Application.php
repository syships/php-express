<?php

namespace syships\zop;



class Application {
    private $baseUrl;
    private $appid;
    private $appsecret;
    private $tag;

    public function __construct($baseUrl, $appid = '4fcf58d7f359f05b643a7', $appsecret = '78bdd4970c57b71b82c62072f700b654', $tag = 'your_tag') {
        $this->baseUrl = $baseUrl;
        $this->appid = $appid;
        $this->appsecret = $appsecret;
        $this->tag = $tag;
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
