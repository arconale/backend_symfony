<?php

namespace App\Service;

use App\Entity\Order;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use App\Service\Contracts\OrderManagerInterface;

class OrderManager implements OrderManagerInterface {

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */

     public function createOrder() : Order {
         return new Order();
     }

     /**
     * {@inheritdoc}
     */
    public function saveOrder(Order $order, $flush = true){
        $this->entityManager->persist($order);
        $flush && $this->entityManager->flush();
    }

     /**
     * @return EntityRepository
     */
    protected function getRepository()
    {
        return $this->entityManager->getRepository(Order::class);
    }
}