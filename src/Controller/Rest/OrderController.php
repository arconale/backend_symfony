<?php

namespace App\Controller\Rest;
use FOS\RestBundle\Controller\Annotations as Rest;
use Doctrine\Common\Collections\Criteria;
use FOS\RestBundle\Controller\AbstractFOSRestController as FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Contracts\OrderManagerInterface;
use Symfony\Component\HttpFoundation\Request;
/**
 * Order controller.
 * @Route("/", name="api_")
 */
class OrderController extends FOSRestController{

    protected $orderManager;

    public function __construct(OrderManagerInterface $orderManager)
    {
        $this->orderManager = $orderManager;
    }

   /**
   * Lists all orders.
   * @Rest\Get("/orders")
   *
   * @return Response
   */
  public function getOrders(Request $request)
  {
    $criteria = Criteria::create();
    if (($app = $request->query->get('customer'))) {
        $criteria->where(Criteria::expr()->eq('customer', $app));
    }
    if ($keyword = $request->query->get('status')) {
        $criteria->andWhere(Criteria::expr()->contains('status', $keyword));
    }
    $orders = $this->orderManager->findOrdersPager(
        $request->query->get('page', 1),
        $request->query->get('size', 20),
        $criteria
    );
    return $this->view($orders->getCurrentPageResults());
  }

}