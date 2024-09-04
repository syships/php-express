<?php
namespace logistic\yto;

/**
 * 圆通sdk,模仿中通
 *
 * @author xu <syships.cn@gmail.com>
 * @since 2024-08-13
 */
class YtoRequest
{
    private $url;
    private $method;
    private $params = Array();
    private $body;

    public function addParam($k, $v)
    {
        $this->params += [$k => $v];
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    public function setData($data)
    {
        $this->params = json_decode($data);
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function getBody()
    {
        return $this->body;
    }



    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }


}
