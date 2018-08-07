<?php

namespace Mews\LaravelPos;

use Illuminate\Config\Repository;
use Mews\Pos\Pos;
use Mews\Pos\PosInterface;

/**
 * Class LaravelPos
 * @package Mews\LaravelPos
 */
class LaravelPos
{
    /**
     * @var Repository
     */
    protected $config;

    /**
     * @var array
     */
    protected $account;

    /**
     * @var Pos
     */
    protected $pos;

    /**
     * @var PosInterface
     */
    public $bank;

    /**
     * @var object
     */
    public $response;

    /**
     * Constructor
     *
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        $this->config = $config->get('laravel-pos');
    }

    /**
     * Instance
     *
     * @return $this
     */
    public function instance()
    {
        return $this;
    }

    /**
     * Set custom configuration
     *
     * @param array $config
     * @return LaravelPos
     */
    public function config(array $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Set account and create Pos Object
     *
     * @param array $account
     * @return $this
     * @throws \Mews\Pos\Exceptions\BankClassNullException
     * @throws \Mews\Pos\Exceptions\BankNotFoundException
     */
    public function account(array $account)
    {
        $this->account = $account;

        $this->pos = new Pos($this->account, $this->config);
        $this->bank = $this->pos->bank;

        return $this;
    }

    /**
     * Prepare Order
     *
     * @param array $order
     * @return $this
     */
    public function prepare(array $order)
    {
        $this->pos->prepare($order);

        return $this;
    }

    /**
     * Payment
     *
     * @param array $card
     * @return $this
     */
    public function payment(array $card)
    {
        $this->pos->payment($card);

        $this->response = $this->pos->bank->response;

        return $this;
    }
}