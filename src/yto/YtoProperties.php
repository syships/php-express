<?php
namespace logistic\yto;

/**
 * 圆通sdk,模仿中通
 *
 * @author xu <syships.cn@gmail.com>
 * @since 2024-08-13
 */
class YtoProperties
{
    private $companyid;
    private $key;
    private $version;

    /**
     * YtoProperties constructor.
     * @param $companyid
     * @param $key
     */
    public function __construct($companyid, $key,$version='v1')
    {
        $this->companyid = $companyid;
        $this->key = $key;
        $this->version = $version;
    }

    /**
     * @return mixed
     */
    public function getCompanyid()
    {
        return $this->companyid;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }
    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }


}
