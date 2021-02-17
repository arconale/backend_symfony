<?php

namespace App\Service\Contracts;

use App\Entity\Customer;

interface CustomerManagerInterface {

    /**
     * Create Customer
     * @return Customer
     */
    public function createCustomer() : Customer;

    /**
     * Save Customer
     * @param Customer $customer
     * @param boolean $flush
     * @return Customer
     */

     public function saveCustomer(Customer $customer, $flush = true) : Customer;

     /**
      * Find Customer by fullName
      * @param string fullName
      * @return Customer
      */
      public function findCustomerByFullName(string $fullName);
}