<?php

use Phalcon\Mvc\Controller;

class OrderController extends Controller
{

    public function indexAction()
    {
        if($this->session->get("user") == null) {
            $this->response->redirect("login");
        } else {
            $user = $this->session->get("user");
            $total =$this->request->get("total");
            $quantity = $this->request->get("quantity");
            $items= $this->request->get("cart");

            if($total==0 || $quantity==0 || $items == null) {
                $this->flashSession->error("Add Products");
                $this->response->redirect("cart");
            }
           

            $order = new Orders();
            $order->assign(
                [
                    "user_id" => $user->user_id,
                    "items" =>$items,
                    "totalprice" => $total,
                    "quantity" => $quantity,
                    "status" => "pending"
                ],
                [
                    "user_id",
                    "items",
                    "totalprice",
                    "quantity",
                    "status"
                ]
                );
                if($order->save()) {
                    $this->flashSession->success("Order placed!!");
                    $this->response->redirect("cart");
                    $cart = Cart::find("user_id='$user->user_id'");
                    foreach($cart as $c) {
                        $c->delete();
                    } 
                } else {
                    foreach($order->getMessages() as $m)
                    {
                        $this->flashSession->error($m);
                        $this->response->redirect("cart");
                        return;
                    }
                }
        }
    }
}