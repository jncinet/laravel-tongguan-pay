<?php
/**
 * 通用方法
 *
 * User: zhangye
 * Date: 2020/8/17
 * Time: 9:59 AM
 */

namespace Qihucms\TongGuanPay\Gateways;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Qihucms\TongGuanPay\Exceptions\InvalidArgumentException;
use Qihucms\TongGuanPay\Exceptions\InvalidSignException;

trait Pay
{
    /**
     * @param null $data
     * @return Collection
     * @throws InvalidSignException
     */
    public function verify($data = null): Collection
    {
        if (is_null($data)) {
            $request = Request::createFromGlobals();

            $data = $request->request->count() > 0 ? $request->request->all() : $request->query->all();
        }

        if (Support::VerifySign($data['sign'], $data['lowOrderId'])) {
            return new Collection($data);
        }

        throw new InvalidSignException('Alipay Sign Verify FAILED', $data);
    }

    /**
     * @param array $order
     * @return Collection
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws InvalidArgumentException
     */
    public function find(array $order): Collection
    {
        if (!array_key_exists('trade_no', $order) && !array_key_exists('out_trade_no', $order)) {
            throw new InvalidArgumentException('系统订单号(out_trade_no)与通莞金服订单号(trade_no)必须设置一项');
        }
        return Support::requestApi(self::URL['query'], $order);
    }

    /**
     * @param array $order
     * @return Collection
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function refund(array $order): Collection
    {
        if (!array_key_exists('refundMoney', $order)) {
            throw new InvalidArgumentException('退款金额(refundMoney)必须填写');
        }
        if (!array_key_exists('trade_no', $order)) {
            throw new InvalidArgumentException('通莞订单号(trade_no)必须填写');
        }
        // 如果设置了退款单号，则视为多次退款
        if (array_key_exists('lowRefundNo', $order)) {
            return Support::requestApi(self::URL['refund_many'], $order);
        }
        return Support::requestApi(self::URL['refund'], $order);
    }

    /**
     * @param array $order
     * @return Collection
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function query(array $order)
    {
        // 退款查询
        if (count($order) > 0 && !array_key_exists('refundNo', $order)) {
            return Support::requestApi(self::URL['query_refund'], $order);
        }
        // 列表查询
        if (count($order) > 0 && array_key_exists('trade_no', $order)) {
            return Support::requestApi(self::URL['query_many_refund'], $order);
        }
        throw new InvalidArgumentException('参数错误');
    }

    /**
     * @param array $order
     * @return Collection
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function cancel(array $order): Collection
    {
        if (!array_key_exists('out_trade_no', $order)) {
            throw new InvalidArgumentException('系统订单号(out_trade_no)必须填写');
        }
        if (!array_key_exists('trade_no', $order)) {
            throw new InvalidArgumentException('通莞订单号(trade_no)必须填写');
        }
        return Support::requestApi(self::URL['cancel'], $order);
    }

    /**
     * @param array $order
     * @return Collection
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function close(array $order): Collection
    {
        if (!array_key_exists('out_trade_no', $order)) {
            throw new InvalidArgumentException('系统订单号(out_trade_no)必须填写');
        }
        if (!array_key_exists('trade_no', $order)) {
            throw new InvalidArgumentException('通莞订单号(trade_no)必须填写');
        }
        return Support::requestApi(self::URL['cancel'], $order);
    }

    /**
     * @return Response
     */
    public function success(): Response
    {
        return new Response('SUCCESS');
    }

    /**
     * @param array $data
     * @return \Illuminate\Http\Response|Collection
     * @throws InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function qrcode($data = [])
    {
        if (!array_key_exists('out_trade_no', $data)) {
            throw new InvalidArgumentException('系统订单号(out_trade_no)必须填写');
        }

        if (!array_key_exists('total_amount', $data)) {
            throw new InvalidArgumentException('支付金额(total_amount)必须填写');
        }

        if (!array_key_exists('body', $data)) {
            throw new InvalidArgumentException('商品描述(body)必须填写');
        }

        return Support::requestApi(self::URL['qrcode'], $data);
    }
}