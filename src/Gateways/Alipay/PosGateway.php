<?php
/**
 * 主扫支付
 * User: zhangye
 * Date: 2020/8/16
 * Time: 3:42 PM
 */

namespace Qihucms\TongGuanPay\Gateways\Alipay;

use Qihucms\TongGuanPay\Contracts\GatewayInterface;
use Qihucms\TongGuanPay\Exceptions\InvalidArgumentException;
use Qihucms\TongGuanPay\Gateways\Support;

class PosGateway implements GatewayInterface
{
    const URL = 'http://ipay.833006.net/tgPosp/services/payApi/unifiedorder';

    /**
     * @param array $data
     * @return \Illuminate\Http\Response|\Illuminate\Support\Collection
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * 参数：
     * account 聚合支付账号
     * payMoney 单位：元
     * lowOrderId 下游订单号
     * body 商品订单描述
     * sign 签名
     * notifyUrl 接收支付结果的url地址
     * payType 支付方式 0：微信，1：支付宝，4：银联
     */
    public function pay($data = [])
    {
        if (!array_key_exists('total_amount', $data) || !array_key_exists('notify_url', $data) || !array_key_exists('body', $data) || !array_key_exists('out_trade_no', $data)) {
            throw new InvalidArgumentException('参数错误');
        }
        return Support::requestApi(self::URL, array_merge($data, ['payType' => '1']));
    }
}