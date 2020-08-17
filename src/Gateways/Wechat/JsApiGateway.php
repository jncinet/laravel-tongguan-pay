<?php
/**
 * Created by PhpStorm.
 * User: zhangye
 * Date: 2020/8/17
 * Time: 2:11 PM
 */

namespace Qihucms\TongGuanPay\Gateways\Wechat;

use Qihucms\TongGuanPay\Contracts\GatewayInterface;
use Qihucms\TongGuanPay\Exceptions\InvalidArgumentException;
use Qihucms\TongGuanPay\Gateways\Support;

class JsApiGateway implements GatewayInterface
{
    const URL = 'http://tgjf.833006.biz/tgPosp/services/payApi/wxJspay';

    /**
     * @param array $order
     * @return \Illuminate\Http\Response|\Illuminate\Support\Collection
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function pay($order = [])
    {
        if (!array_key_exists('openId', $order) || !array_key_exists('appId', $order) || !array_key_exists('notify_url', $order) || !array_key_exists('out_trade_no', $order) || !array_key_exists('total_amount', $order) || !array_key_exists('body', $order)) {
            throw new InvalidArgumentException('参数错误');
        }
        // 异步回调，缓存签名
        $data['account'] = config('tongguan.account', '13974747474');
        $data['sign'] = Support::GenerateSign($data, false);

        return Support::requestApi(self::URL, $data);
    }
}