<?php

namespace express\zop;

use express\LogisticsActionInterface;

/**
 * 中通操作类
 * 
 * 提供中通物流的操作接口，包括拦截、取消拦截、查询拦截状态、查询物流轨迹和订阅物流轨迹功能。
 * 
 * @author xu <syships.cn@gmail.com>
 * @since 2024-08-09
 */
class ZopApplication implements LogisticsActionInterface{
    private $baseUrl;
    private $appId;
    private $appSecret;
    private $property;

    /**
     * 构造函数
     * 
     * @param array $config 配置文件
     */
    public function __construct(array $config=[]) {
        $this->appId = $config['app_id'];
        $this->appSecret = $config['app_secret'];
        $this->baseUrl = $config['server_url'];
    }

    /**
     * 获取 ZopProperties 实例
     * 
     * @return ZopProperties
     */
    public function getProperty(): ZopProperties {
        if ($this->property === null) {
            $this->property = new ZopProperties($this->appId, $this->appSecret);
        }
        return $this->property;
    }

    /**
     * 执行 API 请求
     * 
     * @param string $action API 路由方法名
     * @param array $data 请求参数
     * @return array 解码后的响应数据
     */
    private function call(string $action, array $data): array {
        $client = new ZopClient($this->getProperty());
        $request = new ZopRequest();
        $request->setUrl($this->baseUrl . $action);
        $request->setData(json_encode($data));
        $response = $client->execute($request);
        return json_decode($response, true);
    }

    /**
     * 发起拦截请求
     * 
     * 参考文档：https://open.zto.com/#/interfaces?schemeCode=&resourceGroup=40&apiName=thirdcenter.createIntercept
     * 
     * @param array $data 请求数据
     * @return array 响应数据
     */
    public function intercept(array $data): array {
        return $this->call('thirdcenter.createIntercept', $data);
    }

    /**
     * 发起拦截修改请求
     * 
     * 参考文档：https://open.zto.com/#/interfaces?schemeCode=&resourceGroup=40&apiName=thirdcenter.createIntercept
     * 
     * @param array $data 请求数据
     * @return array 响应数据
     */
    public function interceptChange(array $data): array {
        return $this->intercept($data);
    }

    /**
     * 发起取消拦截请求
     * 
     * 参考文档：https://open.zto.com/#/interfaces?schemeCode=&resourceGroup=40&apiName=thirdcenter.cancelIntercept
     * 
     * @param array $data 请求数据
     * @return array 响应数据
     */
    public function interceptCancel(array $data): array {
        return $this->call('thirdcenter.cancelIntercept', $data);
    }

    /**
     * 查询拦截状态
     * 
     * 参考文档：https://open.zto.com/#/interfaces?resourceGroup=40&apiName=thirdcenter.queryInterceptAndReturnStatus
     * 
     * @param array $data 请求数据
     * @return array 响应数据
     */
    public function interceptStatus(array $data): array {
        return $this->call('thirdcenter.queryInterceptAndReturnStatus', $data);
    }

    /**
     * 查询物流轨迹
     * 
     * 参考文档：https://open.zto.com/#/interfaces?resourceGroup=10&apiName=zto.merchant.waybill.track.query
     * 
     * @param array $data 请求数据
     * @return array 响应数据
     */
    public function track(array $data): array {
        return $this->call('zto.merchant.waybill.track.query', $data);
    }

    /**
     * 订阅物流轨迹
     * 
     * 参考文档：https://open.zto.com/#/interfaces?resourceGroup=10&apiName=zto.merchant.waybill.track.subsrcibe
     * 
     * @param array $data 请求数据
     * @return array 响应数据
     */
    public function trackSubscribe(array $data): array {
        return $this->call('zto.merchant.waybill.track.subsrcibe', $data);
    }
    
}
