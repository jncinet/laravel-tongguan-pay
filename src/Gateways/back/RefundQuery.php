<?php
/**
 * 退款查询
 *
 * User: zhangye
 * Date: 2020/8/15
 * Time: 10:49 AM
 */

namespace Qihucms\TongGuanPay\Gateways;

use Qihucms\TongGuanPay\Contracts\GatewayInterface;
use Qihucms\TongGuanPay\Exceptions\InvalidArgumentException;

class RefundQuery implements GatewayInterface
{
    const URL = 'http://tgjf.833006.biz/tgPosp/services/payApi/queryRefund';

    /**
     * @param array $data
     * @return \Illuminate\Http\Response|\Illuminate\Support\Collection
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request($data = [])
    {
        if (!array_key_exists('refundNo', $data)) {
            throw new InvalidArgumentException('参数错误');
        }

        return Support::requestApi(self::URL, $data);
    }
}