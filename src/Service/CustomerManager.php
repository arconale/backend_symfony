<?php

namespace App\Service;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use App\Service\Contracts\CustomerManagerInterface;
use App\Entity\Customer;

class CustomerManager implements CustomerManagerInterface {
    
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
    public function createCustomer() : Customer{
        return new Customer();
    }

    /**
     * {@inheritdoc}
     */
    public function saveCustomer(Customer $customer, $flush = true) : Customer{
        $this->entityManager->persist($customer);
        $flush && $this->entityManager->flush();
        return $customer;
    }

    /**
     * {@inheritdoc}
     */
    public function findCustomerByFullName(string $fullName) {

        return $this->getRepository()->findOneByFullName($fullName);
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository()
    {
        return $this->entityManager->getRepository(Customer::class);
    }
}