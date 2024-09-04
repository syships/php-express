<?php
namespace logistic\jt;

/**
 * 极兔sdk,模仿中通
 *
 * @author xu <syships.cn@gmail.com>
 * @since 2024-08-17
 */
class JtProperties
{
    private $companyid;
    private $key;

    /**
     * JtProperties constructor.
     * @param $companyid
     * @param $key
     */
    public function __construct($companyid, $key)
    {
        $this->companyid = $companyid;
        $this->key = $key;
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




}