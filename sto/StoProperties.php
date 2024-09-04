<?php
namespace syships\logistic\sto;

/**
 * 极兔sdk,模仿中通
 *
 * @author xu <syships.cn@gmail.com>
 * @since 2024-08-19
 */
class StoProperties
{
    private $companyid;
    private $key;
    private $code;

    /**
     * JtProperties constructor.
     * @param $companyid
     * @param $key
     */
    public function __construct($companyid, $key, $code)
    {
        $this->companyid = $companyid;
        $this->key = $key;
        $this->code = $code;
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
    public function getCode()
    {
        return $this->code;
    }




}
