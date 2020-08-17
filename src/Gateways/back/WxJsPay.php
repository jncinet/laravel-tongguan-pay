<?php
/**
 * 微信公众号小程序支付
 *
 * User: zhangye
 * Date: 2020/8/15
 * Time: 10:49 AM
 */

namespace Qihucms\TongGuanPay\Gateways;

use Qihucms\TongGuanPay\Contracts\GatewayInterface;
use Qihucms\TongGuanPay\Exceptions\InvalidArgumentException;

class WxJsPay extends Gateway implements GatewayInterface
{
    const URL = 'http://tgjf.833006.biz/tgPosp/services/payApi/wxJspay';

    /**
     * @param array $data
     * @return \Illuminate\Http\Response|\Illuminate\Support\Collection
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request($data = [])
    {
        if (!array_key_exists('openId', $data) || !array_key_exists('appId', $data) || !array_key_exists('notify_url', $data) || !array_key_exists('out_trade_no', $data) || !array_key_exists('total_amount', $data) || !array_key_exists('body', $data)) {
            throw new InvalidArgumentException('参数错误');
        }
        // 异步回调，缓存签名
        $data['account'] = config('tongguan.account', '13974747474');
        $data['sign'] = Support::GenerateSign($data, false);

        return Support::requestApi(self::URL, $data);
    }

    public function pay()
    {

    }

    public function face()
    {
        
    }

    public function app()
    {
        
    }
}