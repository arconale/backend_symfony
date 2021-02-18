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
    $customer = $request->query->get('customer');
    $status = $request->query->get('status');
    
    if ($customer != "" && $customer != 'null') {
        $criteria->where(Criteria::expr()->eq('c.fullName', $customer));
    }
    if ($status != "" && $status != 'null') {
        $criteria->andWhere(Criteria::expr()->contains('status', $status));
    }
    $orders = $this->orderManager->findOrdersPager(
        $request->query->get('page', 1),
        $request->query->get('size', 20),
        $criteria
    );
    return $this->view($orders->getCurrentPageResults());
  }

  /**
   * Cancel Order
   * @Rest\Post("/orders/cancel")
   * @return Response
   */
  public function cancelOrder(Request $request){
    $data = $request->getContent();
    $data = \json_decode($data,true);
    $id = $data["id"];
    try {
        $this->orderManager->cancelOrder($id);
        return new JsonResponse(["operation" => true]);
    } catch (\Exception $ex) {
        return new JsonResponse(["operation" => false]);
    }
  }

}