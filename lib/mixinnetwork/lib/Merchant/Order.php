<?php
namespace MixinNetwork\Merchant;

use MixinNetwork\MixinNetwork;
use MixinNetwork\Merchant;
use MixinNetwork\OrderIsNotValid;
use MixinNetwork\OrderNotFound;

class Order extends Merchant
{
    private $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function toHash()
    {
        return $this->order;
    }

    public function __get($name)
    {
        return $this->order[$name];
    }

    public static function find($orderId, $options = array(), $authentication = array())
    {
        try {
            return self::findOrFail($orderId, $options, $authentication);
        } catch (OrderNotFound $e) {
            return false;
        }
    }

    public static function findOrFail($orderId, $options = array(), $authentication = array())
    {
        $order = MixinNetwork::request('/orders/' . $orderId, 'GET', array(), $authentication);

        return new self($order);
    }

    public static function create($params, $options = array(), $authentication = array())
    {
        try {
            return self::createOrFail($params, $options, $authentication);
        } catch (OrderIsNotValid $e) {
            return false;
        }
    }

    public static function createOrFail($params, $options = array(), $authentication = array())
    {
        $order = MixinNetwork::request('/orders', 'POST', $params, $authentication);

        return new self($order);
    }
}
