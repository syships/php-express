<?php

namespace logistic\sto;

use logistic\LogisticsActionInterface;

/**
 * 极兔操作类
 * 
 * 提供极兔物流的操作接口，包括拦截、取消拦截、更改拦截信息、查询物流轨迹和订阅物流轨迹功能。
 * 
 * @author xu <syships.cn@gmail.com>
 * @since 2024-08-19
 */
class StoApplication implements LogisticsActionInterface{
    private $baseUrl;
    private $appId;
    private $appSecret;
    private $code;
    private $property;

    /**
     * 构造函数
     * 
     * @param array $config 配置文件
     */
    public function __construct(array $config=[]) {
        $this->appId = $config['app_id'];
        $this->appSecret = $config['app_secret'];
        $this->code = $config['code'];
        $this->baseUrl = $config['server_url'];
    }

    /**
     * 获取 StoProperties 实例
     * 
     * @return StoProperties
     */
    public function getProperty(): StoProperties {
        if ($this->property === null) {
            $this->property = new StoProperties($this->appId, $this->appSecret, $this->code);
        }
        return $this->property;
    }

    /**
     * 执行 API 请求
     * 
     * @param string $action API 路由方法名
     * @param array $data 请求参数
     * @param array $extra 附加参数
     * @return array 解码后的响应数据
     */
    private function call(string $action, array $data, array $extra = []): array {
        $client = new StoClient($this->getProperty());
        $request = new StoRequest();
        $request->setUrl($this->baseUrl);
        $request->setData(json_encode($data));
        $request->setAction($action);
        $request->setExtra($extra);
        $response = $client->execute($request);
        return $this->formatStoRes($response);
    }

    /**
     * 格式化 Sto 接口响应
     * 
     * 将响应转换为数组，支持 JSON 和 XML 格式
     * 
     * @param mixed $response 原始响应数据
     * @return array 转换后的数组
     */
    private function formatStoRes($response): array {
        // 检查 JSON 数据
        if (is_string($response) && substr($response, 0, 1) === '{') {
            $data = json_decode($response, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $data;
            }
        }
        
        // 检查 XML 数据
        if (is_string($response) && substr($response, 0, 10) === '<response>' && substr($response, -11) === '</response>') {
            $data = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
            return json_decode(json_encode($data), true);
        }

        return [];
    }

    /**
     * 发起拦截请求
     * 
     * 参考文档：https://open.sto.cn/#/apiDocument/INTERCEPT_CREATE_STANDARD
     * 
     * @param array $data 请求数据
     * @return array 响应数据
     */
    public function intercept(array $data): array {
        $action = 'INTERCEPT_CREATE_STANDARD';
        $extra = ['to_appkey' => 'reverse-center', 'to_code' => 'reverse-center'];
        return $this->call($action, $data, $extra);
    }

    /**
     * 发起拦截更址请求
     * 
     * 参考文档：https://open.sto.cn/#/apiDocument/INTERCEPT_CREATE_STANDARD
     * 
     * @param array $data 请求数据
     * @return array 响应数据
     */
    public function interceptChange(array $data): array {
        return $this->intercept($data);
    }

    /**
     * 发起拦截取消请求
     * 
     * 参考文档：https://open.sto.cn/#/apiDocument/INTERCEPT_DEL_STANDARD
     * 
     * @param array $data 请求数据
     * @return array 响应数据
     */
    public function interceptCancel(array $data): array {
        $action = 'INTERCEPT_DEL_STANDARD';
        $extra = ['to_appkey' => 'reverse-center', 'to_code' => 'reverse-center'];
        return $this->call($action, $data, $extra);
    }

    /**
     * 订阅物流轨迹
     * 
     * 参考文档：https://open.sto.cn/#/apiDocument/STO_TRACE_PLATFORM_SUBSCRIBE
     * 
     * @param array $data 请求数据
     * @return array 响应数据
     */
    public function trackSubscribe(array $data): array {
        $action = 'STO_TRACE_PLATFORM_SUBSCRIBE';
        $extra = ['to_appkey' => 'sto_trace_platform', 'to_code' => 'sto_trace_platform'];
        return $this->call($action, $data, $extra);
    }

    /**
     * 查询物流轨迹
     * 
     * 参考文档：https://open.sto.cn/#/apiDocument/STO_TRACE_QUERY_COMMON
     * 
     * @param array $data 请求数据
     * @return array 响应数据
     */
    public function track(array $data): array {
        $action = 'STO_TRACE_QUERY_COMMON';
        $extra = ['to_appkey' => 'sto_trace_query', 'to_code' => 'sto_trace_query'];
        return $this->call($action, $data, $extra);
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
