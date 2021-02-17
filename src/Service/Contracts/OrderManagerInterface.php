<?php

namespace App\Service\Contracts;

use App\Entity\Order;
use Doctrine\Common\Collections\Criteria;
use Pagerfanta\Pagerfanta;

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

    /**
     * Get Orders
     *
     * @param int $page
     * @param int|null $size
     * @param Criteria|null $criteria
     * @return Coupon[]|Pagerfanta
     */
    public function findOrdersPager($page, $size = null, Criteria $criteria = null);

    /**
     * Cancel order
     * @param int id
     */
    public function cancelOrder(int $id);

}