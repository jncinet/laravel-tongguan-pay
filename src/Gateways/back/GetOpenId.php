<?php
/**
 * 获取微信openId
 * User: zhangye
 * Date: 2020/8/16
 * Time: 3:42 PM
 */

namespace Qihucms\TongGuanPay\Gateways;

use Qihucms\TongGuanPay\Contracts\GatewayInterface;
use Qihucms\TongGuanPay\Exceptions\InvalidArgumentException;

class GetOpenId implements GatewayInterface
{
    const URL = 'http://tgjf.833006.biz/tgPosp/services/payApi/authCode2openId';

    /**
     * 参数：
     * "appId":"wx3d9ac43c6bb7337a", 用户appId
     * "barcode":"136348972627578033" 支付条码号
     * @param array $data
     * @return \Illuminate\Http\Response|\Illuminate\Support\Collection
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request($data = [])
    {
        if (array_key_exists('appId', $data) && array_key_exists('barcode', $data)) {
            return Support::requestApi(self::URL, $data);
        }
        throw new InvalidArgumentException('参数错误');
    }
}