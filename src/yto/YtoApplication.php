<?php
namespace logistic\yto;

use logistic\LogisticsActionInterface;

/**
 * 圆通公共调用类
 * 
 * 提供圆通物流的操作接口，包括拦截、取消拦截、更改拦截信息、查询物流轨迹和订阅物流轨迹功能。
 * 
 * @author xu <syships.cn@gmail.com>
 * @since 2024-08-13
 */
class YtoApplication implements LogisticsActionInterface{
    private $baseUrl;
    private $appId;
    private $version;
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
        $this->version = $config['version'];
        $this->baseUrl = $config['server_url'];
    }

    /**
     * 获取 YtoProperties 实例
     * 
     * @return YtoProperties
     */
    public function getProperty(): YtoProperties {
        if ($this->property === null) {
            $this->property = new YtoProperties($this->appId, $this->appSecret, $this->version);
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
        $client = new YtoClient($this->getProperty());
        $request = new YtoRequest();
        $request->setUrl($this->baseUrl . '/open/' . $action . "/v1/HtpG1F/K9991024989");
        $request->setMethod($action);
        $request->setData(json_encode($data));
        $response = $client->execute($request);
        return json_decode($response, true);
    }

    /**
     * 发起拦截请求
     * 
     * 参考文档：https://open.yto.net.cn/interfaceDocument/menu295/submenu309
     * 
     * @param array $data 请求数据
     * @return array 响应数据
     */
    public function intercept(array $data): array {
        return $this->call('wanted_report_adapter', $data);
    }

    /**
     * 发起拦截更址请求
     * 
     * 参考文档：https://open.yto.net.cn/interfaceDocument/menu295/submenu308
     * 
     * @param array $data 请求数据
     * @return array 响应数据
     */
    public function interceptChange(array $data): array {
        return $this->call('wanted_change_adapter', $data);
    }

    /**
     * 发起拦截取消请求
     * 
     * 参考文档：https://open.yto.net.cn/interfaceDocument/menu295/submenu310
     * 
     * @param array $data 请求数据
     * @return array 响应数据
     */
    public function interceptCancel(array $data): array {
        return $this->call('wanted_cancel_adapter', $data);
    }

    /**
     * 查询物流轨迹
     * 
     * 参考文档：https://open.yto.net.cn/interfaceDocument/menu251/submenu258
     * 
     * @param array $data 请求数据
     * @return array 响应数据
     */
    public function track(array $data): array {
        return $this->call('track_query_adapter', $data);
    }

    /**
     * 订阅物流轨迹
     * 
     * 参考文档：https://open.yto.net.cn/interfaceDocument/menu251/submenu301
     * 
     * @param array $data 请求数据
     * @return array 响应数据
     */
    public function trackSubscribe(array $data): array {
        $subscribeData = [
            'client_id' => $data['clientId'],
            'logistics_interface' => json_encode($data),
            'msg_type' => "online"
        ];
        return $this->call('subscribe_adapter', $subscribeData);
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
