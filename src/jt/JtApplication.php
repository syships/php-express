<?php

namespace logistic\jt;

use logistic\LogisticsActionInterface;

/**
 * 极兔操作类
 *
 * 提供极兔物流的操作接口，包括拦截、取消拦截、更新拦截、查询物流轨迹和订阅物流轨迹功能。
 * 
 * @author xu <syships.cn@gmail.com>
 * @since 2024-08-17
 */
class JtApplication implements LogisticsActionInterface{
    private $baseUrl;
    private $appId;
    private $appSecret;
    private $property;

    /**
     * 构造函数
     * 
     * @param array $config 配置文件
     *  - app_id
     *  - app_secret
     *  - server_url
     */
    public function __construct(array $config=[]) {
        $this->appId = $config['app_id'];
        $this->appSecret = $config['app_secret'];
        $this->baseUrl = $config['server_url'];
    }

    /**
     * 获取 JtProperties 实例
     * 
     * @return JtProperties
     */
    public function getProperty(): JtProperties {
        if ($this->property === null) {
            $this->property = new JtProperties($this->appId, $this->appSecret);
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
        $client = new JtClient($this->getProperty());
        $request = new JtRequest();
        $request->setUrl($this->baseUrl . 'webopenplatformapi/api/' . $action . '?uuid=2bc1436423e247e59e8c2ce0c3b9062c');
        $request->setData(json_encode($data));
        $response = $client->execute($request);
        return json_decode($response, true);
    }

    /**
     * 发起拦截请求
     * 
     * 参考文档：https://open.jtexpress.com.cn/#/apiDoc/other/intercept
     * 
     * @param array $data 请求数据
     * @return array 响应数据
     */
    public function intercept(array $data): array {
        $action = 'waybill/intercept';
        return $this->call($action, $data);
    }

    /**
     * 更新拦截请求
     * 
     * 参考文档：https://open.jtexpress.com.cn/#/apiDoc/other/intercept
     * 
     * @param array $data 请求数据
     * @return array 响应数据
     */
    public function interceptChange(array $data): array {
        return $this->intercept($data);
    }

    /**
     * 取消拦截请求
     * 
     * 参考文档：
     * 
     * @param array $data 请求数据
     * @return array 响应数据
     */
    public function interceptCancel(array $data): array {
        return [
            'code'=>0,
            'msg'=>'操作无效'
        ];
    }

    /**
     * 订阅物流轨迹
     * 
     * 参考文档：https://open.jtexpress.com.cn/#/apiDoc/logistics/subscribe
     * 
     * @param array $data 请求数据
     * @return array 响应数据
     */
    public function trackSubscribe(array $data): array {
        $action = 'trace/subscribe';
        return $this->call($action, $data);
    }

    /**
     * 查询物流轨迹
     * 
     * 参考文档：https://open.jtexpress.com.cn/#/apiDoc/logistics/query
     * 
     * @param array $data 请求数据
     * @return array 响应数据
     */
    public function track(array $data): array {
        $action = 'logistics/trace';
        return $this->call($action, $data);
    }
    
    /**
     * 查询拦截状态
     * 
     * @param array $data 请求数据
     * @return array 响应数据
     */
    public function interceptStatus(array $data): array {
        return [];
    }
}
