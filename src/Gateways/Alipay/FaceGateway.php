<?php
/**
 * Created by PhpStorm.
 * User: zhangye
 * Date: 2020/8/17
 * Time: 2:11 PM
 */

namespace Qihucms\TongGuanPay\Gateways\Alipay;

use Qihucms\TongGuanPay\Contracts\GatewayInterface;
use Qihucms\TongGuanPay\Exceptions\InvalidArgumentException;
use Qihucms\TongGuanPay\Gateways\Support;

class FaceGateway implements GatewayInterface
{
    const URL = 'http://ipay.833006.net/tgPosp/payApi/aliSmile';

    /**
     * @param array $order
     * @return \Illuminate\Http\Response|\Illuminate\Support\Collection
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function pay($order = [])
    {
        if (!array_key_exists('metaInfo', $order)) {
            throw new InvalidArgumentException('刷脸设备信息错误');
        }

        if (!array_key_exists('out_trade_no', $order)) {
            throw new InvalidArgumentException('订单号未设置');
        }

        return Support::requestApi(self::URL, $order);
    }
}