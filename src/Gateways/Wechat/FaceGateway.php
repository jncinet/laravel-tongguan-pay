<?php
/**
 * Created by PhpStorm.
 * User: zhangye
 * Date: 2020/8/17
 * Time: 2:45 PM
 */

namespace Qihucms\TongGuanPay\Gateways\Wechat;

use Qihucms\TongGuanPay\Contracts\GatewayInterface;
use Qihucms\TongGuanPay\Exceptions\InvalidArgumentException;
use Qihucms\TongGuanPay\Gateways\Support;

class FaceGateway implements GatewayInterface
{
    const URL = 'http://ipay.833006.net/tgPosp/payApi/wxFace';

    /**
     * @param array $data
     * @return \Illuminate\Http\Response|\Illuminate\Support\Collection
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function pay($data = [])
    {
        if (!array_key_exists('storeId', $data)) {
            throw new InvalidArgumentException('门店编号(storeId)未填写');
        }

        if (!array_key_exists('storeName', $data)) {
            throw new InvalidArgumentException('门店名称(storeName)未填写');
        }

        if (!array_key_exists('deviceId', $data)) {
            throw new InvalidArgumentException('终端设备号(storeName)未填写');
        }

        if (!array_key_exists('rawdata', $data)) {
            throw new InvalidArgumentException('初始化数据(rawdata)未填写');
        }

        return Support::requestApi(self::URL, $data);
    }
}