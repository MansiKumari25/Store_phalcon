<?php

use Phalcon\Mvc\Model;

class Orders extends Model
{
    public $order_sku;
    public $user_id;
    public $items;
    public $totalprice;
    public $quantity;
    public $date;
    public $status;
}

