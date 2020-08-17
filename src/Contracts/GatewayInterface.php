<?php

namespace Qihucms\TongGuanPay\Contracts;

use Illuminate\Http\Response;
use Illuminate\Support\Collection;

interface GatewayInterface
{
    /**
     * @param array $order
     * @return Response|Collection
     */
    public function pay(array $order);
}