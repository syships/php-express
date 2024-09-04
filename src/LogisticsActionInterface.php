<?php

namespace syships\logistic;

/**
 * 物流服务接口
 *
 * 提供统一的接口定义，用于各种物流服务的操作。
 * 
 * @author xu <syships.cn@gmail.com>
 * @since 2024-08-23
 */
interface LogisticsActionInterface
{
    const ACTION_TRACK = 'track';//轨迹查询
    const ACTION_TRACKSUBSCRIBE = 'trackSubscribe';//轨迹订阅
    const ACTION_INTERCEPT = 'intercept';//拦截回退
    const ACTION_INTERCEPTCHANGE = 'interceptChange';//拦截改址
    const ACTION_INTERCEPTCANCEL = 'interceptCancel';//拦截取消
    const ACTION_INTERCEPTSTATUS = 'interceptStatus';//拦截状态

    const ALL_ACTIONS = [
        self::ACTION_TRACK,
        self::ACTION_TRACKSUBSCRIBE,
        self::ACTION_INTERCEPT,
        self::ACTION_INTERCEPTCHANGE,
        self::ACTION_INTERCEPTCANCEL,
        self::ACTION_INTERCEPTSTATUS
    ];

    /**
     * 发起拦截请求
     *
     * @param array $data 请求数据
     * @return mixed 响应数据
     */
    public function intercept(array $data);

    /**
     * 更新拦截请求
     *
     * @param array $data 请求数据
     * @return mixed 响应数据
     */
    public function interceptChange(array $data);

    /**
     * 取消拦截请求
     *
     * @param array $data 请求数据
     * @return mixed 响应数据
     */
    public function interceptCancel(array $data);

    /**
     * 查询物流轨迹
     *
     * @param array $data 请求数据
     * @return mixed 响应数据
     */
    public function track(array $data);

    /**
     * 订阅物流轨迹
     *
     * @param array $data 请求数据
     * @return mixed 响应数据
     */
    public function trackSubscribe(array $data);

    /**
     * 物流拦截状态
     *
     * @param array $data 请求数据
     * @return mixed 响应数据
     */
    public function interceptStatus(array $data);

    
    /**
     * 物流拦截状态
     * 
     * @return object 响应数据
     */
    public function getProperty();
}
