<?php
/**
 * App支付
 *
 * User: zhangye
 * Date: 2020/8/15
 * Time: 10:49 AM
 */

namespace Qihucms\TongGuanPay\Gateways\Alipay;

use Qihucms\TongGuanPay\Contracts\GatewayInterface;
use Qihucms\TongGuanPay\Exceptions\InvalidArgumentException;
use Qihucms\TongGuanPay\Gateways\Support;

class AppGateway implements GatewayInterface
{
    const URL = 'http://tgjf.833006.biz/tgPosp/services/payApi/appPay';

    /**
     * @param array $data
     * @return \Illuminate\Http\Response|\Illuminate\Support\Collection
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function pay($data = [])
    {
        if (!array_key_exists('body', $data)) {
            throw new InvalidArgumentException('支付金额(body)必须填写');
        }

        if (!array_key_exists('notify_url', $data)) {
            throw new InvalidArgumentException('回调地址(notify_url)必须填写');
        }

        if (!array_key_exists('out_trade_no', $data)) {
            throw new InvalidArgumentException('系统订单号(out_trade_no)必须填写');
        }

        if (!array_key_exists('total_amount', $data)) {
            throw new InvalidArgumentException('支付金额(total_amount)必须填写');
        }

        return Support::requestApi(self::URL, array($data, ['payType' => '1']));
    }
}