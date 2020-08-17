<?php
/**
 * 无效的网关
 *
 * User: zhangye
 * Date: 2020/8/15
 * Time: 10:36 AM
 */

namespace Qihucms\TongGuanPay\Exceptions;

class InvalidGatewayException extends Exception
{
    /**
     * InvalidGatewayException constructor.
     * @param string $message
     * @param array|string $raw
     */
    public function __construct($message, $raw = [])
    {
        parent::__construct('INVALID_GATEWAY: '.$message, $raw, self::INVALID_GATEWAY);
    }
}