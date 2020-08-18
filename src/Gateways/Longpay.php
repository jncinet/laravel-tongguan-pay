<?php
/**
 * 龙支付
 *
 * User: zhangye
 * Date: 2020/8/17
 * Time: 9:26 AM
 */

namespace Qihucms\TongGuanPay\Gateways;

use Illuminate\Support\Str;
use Qihucms\TongGuanPay\Contracts\GatewayApplicationInterface;
use Qihucms\TongGuanPay\Contracts\GatewayInterface;
use Qihucms\TongGuanPay\Exceptions\InvalidGatewayException;

class Longpay implements GatewayApplicationInterface
{
    use Pay;
    /**
     * Const url.
     */
    const URL = [
        'query' => 'http://ipay.833006.net/tgPosp/services/payApi/orderQuery',
        'refund' => 'http://ipay.833006.net/tgPosp/services/payApi/refund',
        'refund_many' => 'http://ipay.833006.net/tgPosp/services/payApi/reverse/v2',
        'query_refund' => 'http://ipay.833006.net/tgPosp/services/payApi/queryRefund',
        'query_many_refund' => 'http://ipay.833006.net/tgPosp/payApi/queryRefundList',
        'cancel' => 'http://ipay.833006.net/tgPosp/services/payApi/reverse',
        'close' => 'http://ipay.833006.net/tgPosp/services/payApi/closeTradeOrder',
        'qrcode' => 'http://ipay.833006.net/tgPosp/services/payApi/allQrcodePay',
    ];

    /**
     * @param $method
     * @param $params
     * @return mixed
     * @throws InvalidGatewayException
     */
    public function __call($method, $params)
    {
        return self::pay($method, ...$params);
    }

    /**
     * @param $gateway
     * @param array $params
     * @return mixed
     * @throws InvalidGatewayException
     */
    public function pay($gateway, $params = [])
    {
        $gateway = get_class($this) . '\\' . Str::studly($gateway) . 'Gateway';

        if (class_exists($gateway)) {
            return $this->makePay($gateway, $params);
        }

        throw new InvalidGatewayException("Pay Gateway [{$gateway}] not exists");
    }

    /**
     * @param string $gateway
     * @param array $params
     * @return \Illuminate\Http\Response|\Illuminate\Support\Collection
     * @throws InvalidGatewayException
     */
    protected function makePay(string $gateway, $params = [])
    {
        $app = new $gateway();

        if ($app instanceof GatewayInterface) {
            return $app->pay($params);
        }

        throw new InvalidGatewayException("Pay Gateway [{$gateway}] Must Be An Instance Of GatewayInterface");
    }
}