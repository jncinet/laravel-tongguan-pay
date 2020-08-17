<?php
/**
 * 被扫支付
 *
 * User: zhangye
 * Date: 2020/8/15
 * Time: 3:59 PM
 */

namespace Qihucms\TongGuanPay\Gateways\Bestpay;

use Illuminate\Support\Collection;
use Qihucms\TongGuanPay\Contracts\GatewayInterface;
use Qihucms\TongGuanPay\Exceptions\InvalidArgumentException;
use Qihucms\TongGuanPay\Gateways\Support;

class MicroGateway implements GatewayInterface
{
    const URL = 'http://tgjf.833006.biz/tgPosp/services/payApi/micropay';

    /**
     * @param array $data
     * @return \Illuminate\Http\Response|Collection
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function pay($data = [])
    {
        if (!array_key_exists('total_amount', $data) || !array_key_exists('barcode', $data) || !array_key_exists('out_trade_no', $data)) {
            throw new InvalidArgumentException('参数错误');
        }
        // 场景值 bar_code:扫码支付 security_code:刷脸支付
        if (array_key_exists('scene', $data) && !in_array($data['scene'], ['bar_code', 'security_code'])) {
            throw new InvalidArgumentException('支付场景值参数错误');
        } else {
            // 设备参数(当刷脸支付时必传)
            if ($data['scene'] === 'security_code' && !array_key_exists('terminalParams', $data)) {
                throw new InvalidArgumentException('刷脸支付设备参数未设置');
            }
        }
        // 单品优惠活动该字段必传，且必须按照规范上传，JSON格式
        if (array_key_exists('detail', $data)) {
            $data['detail'] = json_encode($data['detail'], JSON_UNESCAPED_UNICODE);
        }

        return Support::requestApi(self::URL, array_merge($data, ['payType' => '8']));
    }
}