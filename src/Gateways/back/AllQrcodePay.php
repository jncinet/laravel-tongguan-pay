<?php
/**
 * 一码付接口
 *
 * User: zhangye
 * Date: 2020/8/15
 * Time: 3:59 PM
 */

namespace Qihucms\TongGuanPay\Gateways;

use Illuminate\Support\Collection;
use Qihucms\TongGuanPay\Contracts\GatewayInterface;
use Qihucms\TongGuanPay\Exceptions\InvalidArgumentException;

class AllQrcodePay implements GatewayInterface
{
    const URL = 'http://tgjf.833006.biz/tgPosp/services/payApi/allQrcodePay';

    /**
     * @param array $data
     * @return \Illuminate\Http\Response|Collection
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function pay($data = [])
    {
        if (!array_key_exists('out_trade_no', $data)) {
            throw new InvalidArgumentException('系统订单号(out_trade_no)必须填写');
        }

        if (!array_key_exists('total_amount', $data)) {
            throw new InvalidArgumentException('支付金额(total_amount)必须填写');
        }

        if (!array_key_exists('body', $data)) {
            throw new InvalidArgumentException('商品描述(body)必须填写');
        }

        return Support::requestApi(self::URL, $data);
    }
}