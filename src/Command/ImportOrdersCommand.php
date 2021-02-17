<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\Contracts\CustomerManagerInterface;
use App\Service\Contracts\OrderManagerInterface;


class ImportOrdersCommand extends Command
{
    protected static $defaultName = 'app:importOrders';
    protected static $defaultDescription = 'Import orders from JSON file';
    protected $customerManager;
    protected $orderManager;

    public function __construct(CustomerManagerInterface $customerManager, OrderManagerInterface $orderManager){
        parent::__construct();
        $this->customerManager = $customerManager;
        $this->orderManager = $orderManager;
    }

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('file', InputArgument::REQUIRED, 'Path to the JSON file')
            //->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $path = $input->getArgument('file');

        if (!file_exists($path)){
            $io->error('The file not exists');
            return 0;
        }
        $file = \file_get_contents($path);
        $objFile = \json_decode($file,true);

        foreach($objFile as $order){
            $customer = $this->customerManager->createCustomer(); //create Customer object
            if(!$this->customerManager->findCustomerByFullName($order["customer"])){
                $customer->setFullName($order["customer"]);
                $customer->setAddress1($order["address1"]);
                $customer->setCity($order["city"]);
                $customer->setPostCode($order["postcode"]);
                $customer->setCountry($order["country"]);
                //save customer to db
                $this->customerManager->saveCustomer($customer);
                $orderObj = $this->orderManager->createOrder();
                $orderObj->setCustomer($customer);
                $orderObj->setAmount($order["amount"]);
                $orderObj->setId(intval($order["id"]));
                $orderObj->setDate(new \DateTime($order["date"]));
                $orderObj->setStatus($order["status"]);
                $orderObj->setDeleted($order["deleted"]);
                $orderObj->setLastModified(new \DateTime($order["last_modified"]));
                $this->orderManager->saveOrder($orderObj);
            }
        }

        $io->success('You have import the orders to the database.');

        return 0;
    }
}
