<?php
namespace logistic;
/**
 * 物流调用类
 * 
 * 提供物流操作接口，包括：
 * - `intercept`：拦截回退
 * - `interceptChange`：拦截改址
 * - `interceptCancel`：拦截取消
 * - `interceptStatus`：拦截状态（仅支持中通，接口尚未完善）
 * - `track`：物流轨迹查询
 * - `trackSubscribe`：物流轨迹订阅
 *
 * 相关接口文档地址：
 *
 * - 物流轨迹查询：
 *   - 中通: https://open.zto.com/#/interfaces?resourceGroup=10&apiName=zto.merchant.waybill.track.query
 *   - 圆通: https://open.yto.net.cn/interfaceDocument/menu251/submenu258
 *   - 申通: https://open.sto.cn/index.html#/apiDocument/STO_TRACE_QUERY_COMMON
 *   - 极兔: https://open.jtexpress.com.cn/#/apiDoc/logistics/query
 *
 * - 物流轨迹订阅：
 *   - 中通: https://open.zto.com/#/interfaces?resourceGroup=10&apiName=zto.merchant.waybill.track.subsrcibe
 *   - 圆通: https://open.yto.net.cn/interfaceDocument/menu251/submenu301
 *   - 申通: https://open.sto.cn/index.html#/apiDocument/STO_TRACE_PLATFORM_SUBSCRIBE
 *   - 极兔: https://open.jtexpress.com.cn/#/apiDoc/logistics/subscribe
 *
 * - 拦截回退：
 *   - 中通: https://open.zto.com/#/interfaces?schemeCode=&resourceGroup=40&apiName=thirdcenter.createIntercept
 *   - 圆通: https://open.yto.net.cn/interfaceDocument/menu295/submenu309
 *   - 申通: https://open.sto.cn/index.html#/apiDocument/INTERCEPT_CREATE_STANDARD
 *   - 极兔: https://open.jtexpress.com.cn/#/apiDoc/other/intercept
 *
 * - 拦截改址：
 *   - 中通: https://open.zto.com/#/interfaces?schemeCode=&resourceGroup=40&apiName=thirdcenter.createIntercept
 *   - 圆通: https://open.yto.net.cn/interfaceDocument/menu295/submenu308
 *   - 申通: https://open.sto.cn/index.html#/apiDocument/INTERCEPT_CREATE_STANDARD
 *   - 极兔: https://open.jtexpress.com.cn/#/apiDoc/other/intercept
 *
 * - 拦截取消：
 *   - 中通: https://open.zto.com/#/interfaces?schemeCode=&resourceGroup=40&apiName=thirdcenter.cancelIntercept
 *   - 圆通: https://open.yto.net.cn/interfaceDocument/menu295/submenu310
 *   - 申通: https://open.sto.cn/index.html#/apiDocument/INTERCEPT_DEL_STANDARD
 *   - 极兔: 
 *
 * - 拦截状态（仅支持中通，接口尚未完善）：
 *   - 中通: https://open.zto.com/#/interfaces?resourceGroup=40&apiName=thirdcenter.queryInterceptAndReturnStatus
 *   - 圆通:
*    - 申通:
 *   - 极兔: 
 *
 * 调用示例：
 * <pre>
 * $companyName = "中通";
 * $logistcApp = new LogisticApplication($companyName);
 * $data = [
 *     'waybill_code' => request()->param('waybill_code')
 * ];
 * $res = $logistcApp->track($data);
 * </pre>
 *
 * @method bool track(array $data) 物流轨迹查询
 * - 'waybill_code' => string 必填，物流单号
 * 
 * @method bool trackSubscribe(array $data) 物流轨迹订阅
 * - 'waybill_code' => string 必填，物流单号
 * 
 * @method bool intercept(array $data) 物流拦截回退
 * - 'waybill_code' => string 必填，物流单号
 * - 'intercept_id' => string 中通必填，拦截请求ID
 * - 'desc' => string 圆通必填，描述信息，不可传入空字符串
 * 
 * @method bool interceptChange(array $data) 物流拦截更址
 * - 'waybill_code' => string 必填，物流单号
 * - 'intercept_id' => string 中通必填，拦截请求ID
 * - 'desc' => string 圆通必填，描述信息，不可传入空字符串
 * - 'receive_phone' => string 必填，修改收件人电话
 * - 'receive_username' => string 必填，修改收件人姓名
 * - 'receive_address' => string 必填，修改详细地址
 * - 'receive_district' => string 必填，修改地址区
 * - 'receive_city' => string 必填，修改地址市
 * - 'receive_province' => string 必填，修改地址省
 * 
 * @method bool interceptCancel(array $data) 物流拦截取消
 * - 'waybill_code' => string 必填，物流单号
 * - 'third_biz_no' => string 中通必填，外部业务单号
 * - 'deal_remark' => string 中通必填，取消描述
 * 
 * @method bool interceptStatus(array $data) 物流拦截状态
 * - 'waybill_code' => string 必填，物流单号
 * 
 * @since 2024-08-14
 * @author xu <syships.cn@gmail.com>
 */
class LogisticApplication extends LogisticsGateway{


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
        switch ($action) {
            //轨迹订阅
            case LogisticsActionInterface::ACTION_TRACKSUBSCRIBE:
                switch ($providerName) {
                    case self::PROVIDER_ZTO:
                        return [
                            'billCode' => $data['waybill_code'],
                        ];
                    
                    case self::PROVIDER_YTO:
                        return [
                            'clientId' => $this->getServiceProvider()->getProperty()->getCompanyId(),
                            'waybillNo' => $data['waybill_code'],
                        ];

                    case self::PROVIDER_STO:
                        return [
                            'subscribeInfoList' => [
                                ['waybillNo' => $data['waybill_code']]
                            ]
                        ];

                    case self::PROVIDER_JT:
                        return [
                            'Id' => $this->getServiceProvider()->getProperty()->getCompanyId(),
                            'list' => [
                                [
                                    'waybillCode' => $data['waybill_code'],
                                    'traceNode' => '1&2&3&4&5&6&7&8&9&10&11&12&13&14'//1、快件揽收2、入仓扫描（停用）3、发件扫描4、到件扫描5、出仓扫描6、入库扫描7、代理点收入扫描8、快件取出扫描9、出库扫描10、快件签收11、问题件扫描12、安检扫描13、其他扫描14、退件扫描
                                ]
                            ]
                        ];
                    
                    default:
                        return $data;
                }
                
            //轨迹查询
            case LogisticsActionInterface::ACTION_TRACK:
                switch ($providerName) {
                    case self::PROVIDER_ZTO:
                        return [
                            'billCode' => $data['waybill_code']
                        ];
                    case self::PROVIDER_YTO:
                        return [
                            'Number' => $data['waybill_code']
                        ];
                    case self::PROVIDER_STO:
                        return [
                            'waybillNoList' => [$data['waybill_code']],
                            'waybillNo' => $data['waybill_code'],
                            'order' => 'asc'
                        ];
                    case self::PROVIDER_JT:
                        return [
                            'billCodes' => $data['waybill_code']
                        ];
                    default:
                        return $data;
                }
    
            //物流拦截
            case LogisticsActionInterface::ACTION_INTERCEPT:
                switch ($providerName) {
                    case self::PROVIDER_ZTO:
                        return [
                            'billCode' => $data['waybill_code'],
                            'requestId' => $data['intercept_id'],
                            'thirdBizNo' => $data['intercept_id'],
                            'destinationType' => 2
                        ];
                    case self::PROVIDER_YTO:
                        return [
                            'waybillNo' => $data['waybill_code'],
                            'wantedDesc' => $data['desc']
                        ];
                    case self::PROVIDER_STO:
                        return [
                            'waybillNo' => $data['waybill_code'],
                            'interceptForAct' => "DEFAULT"
                        ];
                    case self::PROVIDER_JT:
                        return [
                            'mailNo' => $data['waybill_code'],
                            'applyTypeCode' => 4
                        ];
                    default:
                        return $data;
                }
    
            //物流拦截改址
            case LogisticsActionInterface::ACTION_INTERCEPTCHANGE:
                switch ($providerName) {
                    case self::PROVIDER_ZTO:
                        return [
                            'billCode' => $data['waybill_code'],
                            'requestId' => $data['intercept_id'],
                            'thirdBizNo' => $data['intercept_id'],
                            'receivePhone' => $data['receive_phone'],
                            'receiveUsername' => $data['receive_username'],
                            'receiveAddress' => $data['receive_address'],
                            'receiveDistrict' => $data['receive_district'],
                            'receiveCity' => $data['receive_city'],
                            'receiveProvince' => $data['receive_province'],
                            'destinationType' => 3
                        ];
                    case self::PROVIDER_YTO:
                        return [
                            'waybillNo' => $data['waybill_code'],
                            'receiverTel' => $data['receive_phone'],
                            'receiverName' => $data['receive_username'],
                            'receiveAddress' => $data['receive_address'],
                            'receiveCountyName' => $data['receive_district'],
                            'receiveCityName' => $data['receive_city'],
                            'receiveProvName' => $data['receive_province'],
                            'wantedDesc' => $data['desc']
                        ];
                    case self::PROVIDER_STO:
                        return [
                            'waybillNo' => $data['waybill_code'],
                            'receiverName' => $data['receive_username'],
                            'receiverTel' => $data['receive_phone'],
                            'receiverProvince' => $data['receive_province'],
                            'receiverCity' => $data['receive_city'],
                            'receiverArea' => $data['receive_district'],
                            'receiverAddress' => $data['receive_address'],
                            'interceptForAct' => "DEFAULT"
                        ];
                    case self::PROVIDER_JT:
                        return [
                            'mailNo' => $data['waybill_code'],
                            'applyTypeCode' => 5,
                            'receivePhone' => $data['receive_phone'],
                            'receiveUsername' => $data['receive_username'],
                            'receiveAddress' => $data['receive_address'],
                            'receiveDistrict' => $data['receive_district'],
                            'receiveCity' => $data['receive_city'],
                            'receiveProvince' => $data['receive_province']
                        ];
                    default:
                        return $data;
                }
    
            //物流拦截取消
            case LogisticsActionInterface::ACTION_INTERCEPTCANCEL:
                switch ($providerName) {
                    case self::PROVIDER_ZTO:
                        return [
                            'billCode' => $data['waybill_code'],
                            'thirdBizNo' => $data['intercept_id']
                        ];
                    case self::PROVIDER_YTO:
                        return [
                            'waybillNo' => $data['waybill_code'],
                            'dealRemark' => $data['desc']
                        ];
                    case self::PROVIDER_STO:
                        return [
                            'waybillNo' => $data['waybill_code']
                        ];
                    case self::PROVIDER_JT:
                        return [
                            // 暂无数据
                        ];
                    default:
                        return $data;
                }
    
            //物流拦截状态
            case LogisticsActionInterface::ACTION_INTERCEPTSTATUS:
                switch ($providerName) {
                    case self::PROVIDER_ZTO:
                        return [
                            'billCode' => $data['bill_code']
                        ];
                    case self::PROVIDER_YTO:
                    case self::PROVIDER_STO:
                    case self::PROVIDER_JT:
                        return [
                            // 暂无数据
                        ];
                    default:
                        return $data;
                }
            //默认值
            default:
                return $data;
        }
    }

}
