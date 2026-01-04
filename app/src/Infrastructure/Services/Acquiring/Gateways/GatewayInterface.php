<?php

namespace Infrastructure\Services\Acquiring\Gateways;

use Infrastructure\Services\Acquiring\Responses\GatewayResponseInterface;

interface GatewayInterface
{
    /**
     * Включить отладочный режим
     *
     * @param bool $debug
     * @return mixed
     */
    public function debug(bool $debug = true);

    public function register(array $params): GatewayResponseInterface;

    /**
     * Запрос регистрации оплаты с предавторизацией
     *
     * @param array $params - Параметры запроса
     * @return GatewayResponseInterface
     */
    public function registerPreAuth(array $params): GatewayResponseInterface;

    /**
     * Запрос регистрации оплаты с возможностью автоплатежа
     *
     * @param array $params
     * @return GatewayResponseInterface
     */
    public function registerPreAuthAuto(array $params): GatewayResponseInterface;

    /**
     * Запрос регистрации оплаты при помощи СБП
     *
     * @param array $params
     * @return GatewayResponseInterface
     */
    public function registerSbp(array $params): GatewayResponseInterface;

    /**
     * Запрос завершения оплаты
     *
     * @param array $params - Параметры запроса
     * @return GatewayResponseInterface
     */
    public function deposit(array $params): GatewayResponseInterface;

    /**
     * Запрос возврата на полную сумму
     *
     * @param array $params - Параметры запроса
     * @return GatewayResponseInterface
     */
    public function refund(array $params): GatewayResponseInterface;

    /**
     * Запрос отмены оплаты заказа
     *
     * @param array $params - Параметры запроса
     * @return GatewayResponseInterface
     */
    public function reverse(array $params): GatewayResponseInterface;

    /**
     * Расширенный запрос состояния заказа
     *
     * @param array $params - Параметры запроса
     * @return GatewayResponseInterface
     */
    public function getOrderStatusExtended(array $params): GatewayResponseInterface;

    /**
     * Запрос отмены неоплаченного заказа
     *
     * @param array $params - Параметры запроса
     * @return GatewayResponseInterface
     */
    public function decline(array $params): GatewayResponseInterface;
}
