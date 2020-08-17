<?php

namespace Qihucms\TongGuanPay\Contracts;

use Illuminate\Http\Response;
use Illuminate\Support\Collection;

interface GatewayApplicationInterface
{
    /**
     * @param string $gateway
     * @param array $params
     * @return Collection|Response
     */
    public function pay($gateway, $params);

    /**
     * @param array $data
     * @return Collection
     */
    public function verify(array $data);

    /**
     * @param array $order
     * @return Collection
     */
    public function find(array $order);

    /**
     * @param array $order
     * @return Collection
     */
    public function refund(array $order);

    /**
     * @param array $order
     * @return Collection
     */
    public function query(array $order);

    /**
     * @param array $order
     * @return Collection
     */
    public function cancel(array $order);

    /**
     * @param array $order
     * @return Collection
     */
    public function close(array $order);

    /**
     * @return Response
     */
    public function success();
}