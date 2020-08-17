<?php
/**
 * Created by PhpStorm.
 * User: zhangye
 * Date: 2020/8/15
 * Time: 10:31 AM
 */

namespace Qihucms\TongGuanPay;

use Illuminate\Support\Str;
use Qihucms\TongGuanPay\Contracts\GatewayApplicationInterface;
use Qihucms\TongGuanPay\Exceptions\InvalidGatewayException;

class Tongguan
{
    /**
     * @param string $method
     * @param array $params
     * @return GatewayApplicationInterface
     * @throws InvalidGatewayException
     */
    public function __call($method, $params): GatewayApplicationInterface
    {
        $app = new self(...$params);
        return $app->create($method);
    }

    /**
     * @param $method
     * @return GatewayApplicationInterface
     * @throws InvalidGatewayException
     */
    protected function create($method): GatewayApplicationInterface
    {
        $gateway = __NAMESPACE__ . '\\Gateways\\' . Str::studly($method);

        if (class_exists($gateway)) {
            return self::make($gateway);
        }

        throw new InvalidGatewayException("Gateway [{$method}] Not Exists");
    }

    /**
     * @param $gateway
     * @return GatewayApplicationInterface
     * @throws InvalidGatewayException
     */
    protected function make($gateway): GatewayApplicationInterface
    {
        $app = new $gateway();

        if ($app instanceof GatewayApplicationInterface) {
            return $app;
        }

        throw new InvalidGatewayException("Gateway [{$gateway}] Must Be An Instance Of GatewayInterface");
    }
}