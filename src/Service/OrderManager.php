<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\Customer;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use App\Service\Contracts\OrderManagerInterface;
use App\Service\PaginatorTrait;

class OrderManager implements OrderManagerInterface {

    use PaginatorTrait;

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
     * {@inheritdoc}
     */
    public function findOrdersPager($page, $size = null, Criteria $criteria = null){
        $qb = $this->getRepository()->createQueryBuilder("o");
        $criteria && $qb->addCriteria($criteria);
        return $this->createPaginator($qb->getQuery(), $page, $size);
    }
     /**
     * @return EntityRepository
     */
    protected function getRepository()
    {
        return $this->entityManager->getRepository(Order::class);
    }
    /**
     * {@inheritdoc}
     */
    public function findOrderById(int $id) : Order {
        return $this->getRepository()->find($id);
    }
    /**
     * {@inheritdoc}
     */
    public function cancelOrder(int $id) {
        $order = $this->findOrderById($id);
        if($order){
            $order->setStatus("cancelled");
            $this->entityManager->flush();
        }else{
            throw new Exception("Order not found", 1);
        }
    }
}