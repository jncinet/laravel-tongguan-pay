<?php
/**
 * 多次退款
 *
 * User: zhangye
 * Date: 2020/8/15
 * Time: 10:49 AM
 */

namespace Qihucms\TongGuanPay\Gateways;

use Qihucms\TongGuanPay\Contracts\GatewayInterface;
use Qihucms\TongGuanPay\Exceptions\InvalidArgumentException;

class ReverseMore implements GatewayInterface
{
    const URL = 'http://tgjf.833006.biz/tgPosp/services/payApi/reverse/v2';

    /**
     * @param array $data
     * @return \Illuminate\Http\Response|\Illuminate\Support\Collection
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request($data = [])
    {
        if (!array_key_exists('refundMoney', $data) || !array_key_exists('out_trade_no', $data) || !array_key_exists('trade_no', $data)) {
            throw new InvalidArgumentException('参数错误');
        }

        return Support::requestApi(self::URL, $data);
    }
}