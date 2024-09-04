<?php
namespace syships\logistic\yto;

/**
 * 圆通sdk,模仿中通
 *
 * @author xu <syships.cn@gmail.com>
 * @since 2024-08-13
 */
class YtoClient
{
    private $ytoProperties;

    private $httpClient;
    /**
     * YtoClient constructor.
     * @param $ytoProperties
     */
    public function __construct($ytoProperties)
    {
        $this->ytoProperties = $ytoProperties;
        $this->httpClient = new YtoHttpUtil();
    }

    public function execute($ytoRequest)
    {
        $params = $ytoRequest->getParams();
        $sign = $this->generateSignature(json_encode($params), $ytoRequest->getMethod(), $this->ytoProperties->getVersion(),$this->ytoProperties->getKey());
        $headers = array(
            "Content-Type: application/x-www-form-urlencoded; charset=UTF-8"
        );
        $data = [
            "timestamp" => microtime(),
            "param" => json_encode($params),
            "sign" => $sign,
            "format" => "JSON",
        ];
        return $this->httpClient->post($ytoRequest->getUrl(), $headers, http_build_query($data), 2000);
    }

    /**
     * @param string $param
     * @param string $method
     * @param string $version
     * @param string $appSecret
     * @return string
     */
    function generateSignature(string $param, string $method, string $version, string $appSecret) {
        // 拼接 data
        $data = $param . $method . $version;

        // 拼接 data 和 appSecret
        $toSign = $data . $appSecret;

        // 进行 MD5 加密
        $md5Hash = md5($toSign, true); 

        // 进行 base64 编码
        $base64Encoded = base64_encode($md5Hash);

        return $base64Encoded;
    }

}
