<?php
/**
 * 翼支付JS支付
 *
 * User: zhangye
 * Date: 2020/8/15
 * Time: 10:49 AM
 */

namespace Qihucms\TongGuanPay\Gateways\Bestpay;

use Qihucms\TongGuanPay\Contracts\GatewayInterface;
use Qihucms\TongGuanPay\Exceptions\InvalidArgumentException;
use Qihucms\TongGuanPay\Gateways\Support;

class JsApiGateway implements GatewayInterface
{
    const URL = 'http://tgjf.833006.biz/tgPosp/services/payApi/yzfJspay';

    /**
     * @param array $data
     * @return \Illuminate\Http\Response|\Illuminate\Support\Collection
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function pay($data = [])
    {
        if (!array_key_exists('lowCashier', $data) || !array_key_exists('notify_url', $data) || !array_key_exists('out_trade_no', $data) || !array_key_exists('total_amount', $data)) {
            throw new InvalidArgumentException('参数错误');
        }

        return Support::requestApi(self::URL, $data);
    }
}