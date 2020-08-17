<?php
/**
 * 支付回调
 *
 * User: zhangye
 * Date: 2020/8/15
 * Time: 10:49 AM
 */

namespace Qihucms\TongGuanPay\Gateways;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Qihucms\TongGuanPay\Contracts\GatewayInterface;
use Qihucms\TongGuanPay\Exceptions\InvalidSignException;

class Notify implements GatewayInterface
{
    /**
     * @param array $data
     * @return \Illuminate\Http\Response|Collection
     * @throws InvalidSignException
     */
    public function request($data = [])
    {
        $request = Request::createFromGlobals();

        $data = $request->request->count() > 0 ? $request->request->all() : $request->query->all();

        if (Support::VerifySign($data['sign'], $data['lowOrderId'])) {
            return new Collection($data);
        }

        throw new InvalidSignException('Pay Sign Verify FAILED', $data);
    }
}