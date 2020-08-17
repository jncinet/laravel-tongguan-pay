<?php
/**
 * 无效的参数
 *
 * User: zhangye
 * Date: 2020/8/15
 * Time: 4:52 PM
 */

namespace Qihucms\TongGuanPay\Exceptions;

class InvalidArgumentException extends Exception
{
    /**
     * InvalidArgumentException constructor.
     * @param $message
     * @param array $raw
     */
    public function __construct($message, $raw = [])
    {
        parent::__construct('INVALID_ARGUMENT: ' . $message, $raw, self::INVALID_ARGUMENT);
    }
}