<?php
namespace syships\logistic;

use Exception;
use syships\logistic\jt\JtApplication;
use syships\logistic\sto\StoApplication;
use syships\logistic\yto\YtoApplication;
use syships\logistic\zop\ZopApplication;

/**
 * 物流类公共方法
 * 
 * 1.error:
 * @method void addError(string $error) 添加错误信息
 * @method array getErrors() 获取所有错误信息
 * @method string getFirstError() 获取第一条错误信息
 * 
 * 2.successData:
 * @method mixed getSuccessData() 获取成功操作后的传值
 * 
 * 3.openData:
 * @method mixed getOpenData() 获取开放平台返回的json数据
 * 
 * @author xu <syships.cn@gmail.com>
 * @since 2024-08-26
 */
class LogisticsGateway{
    const PROVIDER_ZTO = '中通';
    const PROVIDER_YTO = '圆通';
    const PROVIDER_STO = '申通';
    const PROVIDER_JT  = '极兔';

    protected const PROVIDERS = [
        self::PROVIDER_ZTO => ZopApplication::class,
        self::PROVIDER_YTO => YtoApplication::class,
        self::PROVIDER_STO => StoApplication::class,
        self::PROVIDER_JT  => JtApplication::class,
    ];

    private $_config=[];//配置信息

    private $_provider_name;//物流公司名称，中通，圆通，申通，极兔

    private $_service_provider;
    
    private $_errors = []; // 错误信息
    
    private $_success_data; //操作成功后，可额外展示的数据
    
    private $_open_data; //访问开放平台返回的数据

    /**
     * 初始化
     * @param string $providerName 物流公司名称，中通，圆通，申通，极兔
     * @param array $config 配置信息
     */
    public function __construct($providerName,$config)
    {
        if(!isset($config['company_name']) || !isset($config['app_id']) || !isset($config['app_secret']) || !isset($config['server_url'])){
            throw new \Exception("Missing company_name or app_id or app_secret or server_url parameters");
        }
        if (!array_key_exists($providerName, self::PROVIDERS)) {
            throw new \Exception("Unknown provider: " . $providerName);
        }
        $this->_provider_name = $config['company_name'];
        $this->_config = $config;
    }
    
    /**
     * 获取物流服务商
     * @return LogisticsInterface
     */
    protected function getServiceProvider():LogisticsActionInterface  {
        if ($this->_service_provider === null) {
            $this->_service_provider = $this->createServiceProvider();
        }
        return $this->_service_provider;
    }

    /**
     * @return LogisticsInterface
     */
    private function createServiceProvider():LogisticsActionInterface {
        $providerClass = self::PROVIDERS[$this->getProviderName()];
        return new $providerClass($this->getConfig());
    }

    /**
     * 设置错误信息
     * 
     * @param string $message //错误信息
     * @return void
     */
    protected function addError(string $message):void
    {
        // 检查错误信息是否已存在于 errors 数组中
        if (!in_array($message, $this->_errors, true)) {
            // 如果不存在，则添加错误信息
            $this->_errors[] = $message;
        }
    }

    /**
     * 赋值成功数据
     * 
     * @param mixed $data //成功信息
     * @return void 
     */
    protected function setSuccessData($data):void
    {
        $this->_success_data = $data;
    }

    /**
     * 赋值开放平台返回数据
     * 
     * @param mixed $data //开放平台信息
     * @return void 
     */
    protected function setOpenData($data):void
    {
        $this->_open_data = $data;
    }

    /**
     * 返回物流商名称
     * 
     * @return string 
     */
    protected function getProviderName():string
    {
        return $this->_provider_name;
    }

    /**
     * 获取配置信息
     * @return array
     */
    protected function getConfig():array
    {
        return $this->_config;
    }

    /**
     * 获取数据
     * @return mixed
     */
    public function getSuccessData()
    {
        if($this->_success_data === null){
            $openData = $this->getOpenData();
            //统一格式后数据
            $formatResponse = $this->formatResponse($openData);
            //存储成功数据
            $this->setSuccessData($formatResponse);
        }
        
        return $this->_success_data;
    }

    /**
     * 获取开放平台数据
     * @return mixed
     */
    public function getOpenData()
    {
        return $this->_open_data;
    }

    /**
     * 获取所有错误信息
     * @return array
     */
    public function getErrors():array
    {
        return $this->_errors;
    }

    /**
     * 获取第一条错误信息
     * @return string
     */
    public function getFirstError():string
    {
        $errors = $this->getErrors();
        return $errors[0]??"";
    }
    
    /**
     * 统一第三方返回格式
     *
     * @param array $response 第三方返回值
     * @param bool $responseOrigin 是否返回原始值,默认false
     * @return array 统一返回格式
     *         - 'success' => bool  通过该字段判断是否成功
     *         - 'message' => string
     *         - 'code' => string
     *         - 'data' => any
     */
    protected function formatResponse(array $response) {
        $responseData = [
            'success' => false,
            'message' => "fail",
            'code' => "",
            'data' => []
        ];

        switch ($this->getProviderName()) {
            case self::PROVIDER_ZTO:
                $responseData = [
                    'success' => $response['status'],
                    'message' => $response['message'],
                    'code' => $response['statusCode'],
                    'data' => $response['result'] ?? $response['data'] ?? []
                ];
                break;
            //圆通返回,仅对以下几种格式尝试封装，圆通返回值比较随意，后面添加新接口需做验证
            //1.{"waybillNo":"YT2819007867584","success":true}
            //2.{"success":false,"code":401,"reason":"请求加密校验失败"}
            //3.{"statusCode":1,"statusMessage":"已有有效拦截件，不能重复发起拦截"}
            //4.{"map":{"YT2819007867584":[]},"code":"1001","success":"true","message":"查询结果为空。"}
            case self::PROVIDER_YTO:
                $responseData['success'] = $response['success'] ?? $response['statusCode'] == 0;
                $responseData['message'] = $response['reason'] ?? $response['statusMessage'] ?? $response['message'] ?? "";
                $responseData['code'] = $response['statusCode'] ?? $response['code'] ?? "";
                $responseData['data'] = array_filter($response, function($key) {
                    return !in_array($key, ['success', 'reason', 'statusMessage', 'message', 'code', 'statusCode', 'data_oberp']);
                }, ARRAY_FILTER_USE_KEY);
                break;
            case self::PROVIDER_STO:
                $responseData = [
                    'success' => $response['success'] === "true",
                    'message' => $response['errorMsg'] ?? "",
                    'code' => $response['errorCode'] ?? "",
                    'data' => $response['data'] ?? []
                ];
                break;
            case self::PROVIDER_JT:
                $responseData = [
                    'success' => $response['code'] == 1,
                    'message' => $response['msg'],
                    'code' => $response['code'],
                    'data' => $response['data'] ?? []
                ];
                break;
        }

        return $responseData;
    }

    /**
     * 判断请求是否成功
     * @return bool
     */
    protected function isSuccess():bool
    {
        $successData = $this->getSuccessData();
        if(!$successData['success']){
            $this->addError($successData['message']);
            return false;
        }

        return true;
    }
    
    /**
     * 处理各方法的调用
     * 
     * 根据传入的方法名和参数，执行相应的数据转换操作，然后调用相应的provider的方法
     * 
     * @param string $name 方法名，即要调用的动态方法名称
     * @param array $arguments 方法的参数数组
     * @return bool 
     */
    public function __call(string $name,array $arguments):bool
    {
        if(!in_array($name,LogisticsActionInterface::ALL_ACTIONS)){
            $this->addError("未开放". $name ."操作");
            return false;
        }
        try{
            // 获取传递的参数
            $data = $arguments[0] ?? [];
            // 获取转换后的值
            $transformedData = $this->transform($name,$this->getProviderName(),$data);
            // 发送请求
            $response = $this->getServiceProvider()->$name($transformedData);
            // 保存返回值
            $this->setOpenData($response);
            // 返回结果
            return $this->isSuccess();
        }catch(Exception $e){
            $this->addError($e->getMessage());
            return false;
        }
    }
    
    /**
     * 数据转换
     * 
     * @param string $action 方法名 
     * @param string $providerName 物流商名称 
     * @param array $data 传参 
     * @return array
     */
    protected function transform($action,$providerName,$data):array
    {
        return [];
    }

}
