<?php
/**
 * Created by PhpStorm.
 * User: zhangye
 * Date: 2020/8/15
 * Time: 10:17 AM
 */

namespace Qihucms\TongGuanPay\Exceptions;

class Exception extends \Exception
{
    const UNKNOWN_ERROR = 9999;

    const INVALID_GATEWAY = 1;

    const INVALID_ARGUMENT = 2;

    const ERROR_GATEWAY = 3;

    const INVALID_SIGN = 4;

    /**
     * Raw error info.
     *
     * @var array
     */
    public $raw;

    /**
     * @param string       $message
     * @param array|string $raw
     * @param int|string   $code
     */
    public function __construct($message = '', $raw = [], $code = self::UNKNOWN_ERROR)
    {
        $this->raw = is_array($raw) ? $raw : [$raw];

        parent::__construct($message, intval($code));
    }
}