<?php
/**
 * 验签
 * 
 * User: zhangye
 * Date: 2020/8/15
 * Time: 10:47 AM
 */

namespace Qihucms\TongGuanPay\Exceptions;

class InvalidSignException extends Exception
{
    /**
     * InvalidSignException constructor.
     * @param $message
     * @param array $raw
     */
    public function __construct($message, $raw = [])
    {
        parent::__construct('INVALID_SIGN: '.$message, $raw, self::INVALID_SIGN);
    }
}