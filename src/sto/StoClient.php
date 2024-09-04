<?php
namespace logistic\sto;

/**
 * 极兔发送请求
 *
 * @author xu <syships.cn@gmail.com>
 * @since 2024-08-19
 */
class StoClient
{
    private $stoProperties;

    private $httpClient;
    /**
     * StoClient constructor.
     * @param $stoProperties
     */
    public function __construct($stoProperties)
    {
        $this->stoProperties = $stoProperties;
        $this->httpClient = new StoHttpUtil();
    }

    public function execute($stoRequest)
    {
        $url = $stoRequest->getUrl();
        $action = $stoRequest->getAction();
        $params = $stoRequest->getParams();
        $extra = $stoRequest->getExtra();
        $headers = array(
            "Content-Type: application/x-www-form-urlencoded; charset=UTF-8"
        );

        $content = json_encode($params, JSON_UNESCAPED_UNICODE);
        $data = [
            'content' => $content,
            'data_digest' => base64_encode(md5($content . $this->stoProperties->getKey(), true)),
            'api_name' => $action,
            'from_appkey' => $this->stoProperties->getCompanyid(),
            'from_code' => $this->stoProperties->getCode(),
            'to_appkey' => isset($extra['to_appkey']) ? $extra['to_appkey'] : "",
            'to_code' => isset($extra['to_code']) ? $extra['to_code'] : "",
        ];
        
        return $this->httpClient->post($url, $headers, http_build_query($data), 2000);

    }

}