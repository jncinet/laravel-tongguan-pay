<?php
/**
 * Created by PhpStorm.
 * User: zhangye
 * Date: 2020/8/15
 * Time: 10:56 AM
 */

namespace Qihucms\TongGuanPay\Gateways;

use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Qihucms\TongGuanPay\Exceptions\InvalidArgumentException;

class Support
{
    /**
     * 请求接口
     *
     * @param string $url
     * @param array $data
     * @return Collection
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function requestApi(string $url, array $data): Collection
    {
        $http = new Client(['verify' => false]);

        $data = self::formatParameter($data);
        $response = $http->request('POST', $url, ['json' => $data]);
        $result = json_decode((string)$response->getBody(), true);

        if (array_key_exists('upOrderId', $result)) {
            $result['trade_no'] = $result['upOrderId'];
            unset($result['upOrderId']);
        }
        if (array_key_exists('payMoney', $result)) {
            $result['total_amount'] = $result['payMoney'];
            unset($result['payMoney']);
        }
        if (array_key_exists('lowOrderId', $result)) {
            $result['out_trade_no'] = $result['lowOrderId'];
            unset($result['lowOrderId']);
        }

        return collect($result);
    }

    /**
     * 参数格式化
     *
     * @param array $parameter
     * @return array
     * @throws InvalidArgumentException
     */
    public static function formatParameter(array $parameter): array
    {
        if (array_key_exists('total_amount', $parameter)) {
            $parameter['payMoney'] = $parameter['total_amount'];
            unset($parameter['total_amount']);
        }
        if (array_key_exists('trade_no', $parameter)) {
            $parameter['upOrderId'] = $parameter['trade_no'];
            unset($parameter['out_trade_no']);
        }
        if (array_key_exists('out_trade_no', $parameter)) {
            $parameter['lowOrderId'] = $parameter['out_trade_no'];
            unset($parameter['out_trade_no']);
        }
        if (array_key_exists('notify_url', $parameter)) {
            $parameter['notifyUrl'] = $parameter['notify_url'];
            unset($parameter['notify_url']);
        }
        if (array_key_exists('return_url', $parameter)) {
            $parameter['returnUrl'] = $parameter['return_url'];
            unset($parameter['return_url']);
        }
        // 易宝使用资金处理类型，可选值： DELAY_SETTLE("延迟结算"); REAL_TIME("实时订单"); REAL_TIME_DIVIDE("实时分账"); SPLIT_ACCOUNT_IN("实时拆分入账")
        if (array_key_exists('fundProcessType', $parameter)) {
            if (in_array($parameter['fundProcessType'], ['DELAY_SETTLE', 'REAL_TIME', 'REAL_TIME_DIVIDE', 'SPLIT_ACCOUNT_IN'])) {
                // 易宝使用 拆分入账/实时分账，分账详情；资 金处理类型为: REAL_TIME_ DIVIDE("实时分账"), SPLIT_ACCOUNT_IN( "实时拆分入账")时，必 填
                if (in_array($parameter['fundProcessType'], ['REAL_TIME_DIVIDE', 'SPLIT_ACCOUNT_IN'])) {
                    if (array_key_exists('divideDetail', $parameter)) {
                        // 易宝使用 实时分账回告商户地址资金处理类型 为： REAL_TIME_DIVIDE("实时分账")时，必填，分账回调需要等渠道分账成功之后才会回调，一般在实时分账后半小时左右
                        if ($parameter['divideDetail'] === 'REAL_TIME_DIVIDE' && !array_key_exists('divideNotifyUrl', $parameter)) {
                            throw new InvalidArgumentException('实时分账回调地址参数不正确');
                        }
                    } else {
                        throw new InvalidArgumentException('拆分入账/实时分账参数不正确');
                    }
                }
            } else {
                throw new InvalidArgumentException('资金处理类型不正确');
            }
        }
        if (!array_key_exists('account', $parameter)) {
            $parameter['account'] = config('tongguan.account', '13974747474');
        }
        if (!array_key_exists('sign', $parameter)) {
            $parameter['sign'] = self::GenerateSign($parameter);
        }
        return $parameter;
    }

    /**
     * 生成签名
     *
     * @param array $data
     * @param bool $sync
     * @return string
     */
    public static function GenerateSign(array $data, $sync = true): string
    {
        ksort($data);
        $data = array_filter($data, function ($value) {
            return '' !== $value && !is_null($value);
        });
        $qs = '';
        foreach ($data as $key => $value) {
            if ($value !== null && $value !== '') {
                $qs .= $key . '=' . $value . '&';
            }
        }
        $sign = strtoupper(md5($qs . 'key=' . config('tongguan.key', '5f61d7f65b184d19a1e006bc9bfb6b2f')));
        if ($sync !== true && array_key_exists('lowOrderId', $data)) {
            Cache::put($sign, $data['lowOrderId'], now()->addHours(48));
        }
        return $sign;
    }

    /**
     * 验证签名
     *
     * @param string $sign
     * @param array|string $data
     * @return bool
     */
    public static function VerifySign($sign, $data): bool
    {
        return is_array($data) ? self::GenerateSign($data) === $sign : Cache::pull($sign) === $data;
    }
}