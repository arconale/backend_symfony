<?php

namespace App\Service\Contracts;

use App\Entity\Order;

interface OrderManagerInterface {
    
    /**
     * Create Order
     * @return Order
     */
    public function createOrder() : Order;

    /**
     * Save Order
     * @param Order $order
     * @param boolean $flush
     */
    public function saveOrder(Order $order, $flush = true);
}