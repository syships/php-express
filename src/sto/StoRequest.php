<?php
namespace logistic\sto;

/**
 * 极兔sdk,模仿中通
 *
 * @author xu <syships.cn@gmail.com>
 * @since 2024-08-19
 */
class StoRequest
{
    private $url;
    private $action;
    private $params = Array();
    private $body;
    private $extra;

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

    public function setData($data)
    {
        $this->params = json_decode($data);
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function setAction($action)
    {
        $this->action = $action;
    }

    public function setExtra($extra)
    {
        $this->extra = $extra;
    }




    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return mixed
     */
    public function getExtra()
    {
        return $this->extra;
    }
    

}