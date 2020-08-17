<?php
/**
 * 支付宝服务窗
 *
 * User: zhangye
 * Date: 2020/8/15
 * Time: 10:49 AM
 */

namespace Qihucms\TongGuanPay\Gateways;

use Qihucms\TongGuanPay\Contracts\GatewayInterface;
use Qihucms\TongGuanPay\Exceptions\InvalidArgumentException;

class ZfbJsPay implements GatewayInterface
{
    const URL = 'http://tgjf.833006.biz/tgPosp/services/payApi/zfbJspay';

    /**
     * @param array $data
     * @return \Illuminate\Http\Response|\Illuminate\Support\Collection
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request($data = [])
    {
        if (!array_key_exists('buyer_id', $data) || !array_key_exists('notify_url', $data) || !array_key_exists('out_trade_no', $data) || !array_key_exists('total_amount', $data) || !array_key_exists('body', $data)) {
            throw new InvalidArgumentException('参数错误');
        }
        // 异步回调，缓存签名
        $data['account'] = config('tongguan.account', '13974747474');
        $data['sign'] = Support::GenerateSign($data, false);

        return Support::requestApi(self::URL, $data);
    }
}