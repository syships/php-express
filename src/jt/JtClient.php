<?php
namespace logistic\jt;

/**
 * 极兔发送请求
 *
 * @author xu <syships.cn@gmail.com>
 * @since 2024-08-17
 */
class JtClient
{
    private $jtProperties;

    private $httpClient;
    /**
     * JtClient constructor.
     * @param $jtProperties
     */
    public function __construct($jtProperties)
    {
        $this->jtProperties = $jtProperties;
        $this->httpClient = new JtHttpUtil();
    }

    public function execute($jtRequest)
    {
        $url = $jtRequest->getUrl();
        $params = $jtRequest->getParams();
        $dataDigest = base64_encode(pack('H*',strtoupper(md5(json_encode($params,JSON_UNESCAPED_UNICODE).$this->jtProperties->getKey()))));
        $headers = array(
            "Content-Type: application/x-www-form-urlencoded; charset=UTF-8",
            "apiAccount: " .$this->jtProperties->getCompanyid(),
            "digest: " .$dataDigest,
            "timestamp: ".microtime(true) // 微秒时间戳
        );
        $data = [
            'bizContent' => json_encode($params, JSON_UNESCAPED_UNICODE)
        ];
        
        return $this->httpClient->post($url, $headers, http_build_query($data), 2000);

    }
}