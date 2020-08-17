<?php
/**
 * 网关错误
 *
 * User: zhangye
 * Date: 2020/8/15
 * Time: 10:46 AM
 */

namespace Qihucms\TongGuanPay\Exceptions;

class GatewayException extends Exception
{
    /**
     * GatewayException constructor.
     * @param $message
     * @param array $raw
     * @param $code
     */
    public function __construct($message, $raw = [], $code = self::ERROR_GATEWAY)
    {
        parent::__construct('ERROR_GATEWAY: '.$message, $raw, $code);
    }
}