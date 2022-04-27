<?php

use Phalcon\Mvc\Controller;


class CartController extends Controller
{

    public function indexAction()
    {
        if ($this->session->get("user") == null) {
            $this->response->redirect("login");
        } else {
            $user=$this->session->get("user");
            $cart = Cart::find("user_id='$user->user_id'");
            foreach($cart as $c) {
                $product = Products::findFirst("product_sku='$c->product_id'");
                $products [] = $product;
            } 
            $this->view->cart = $cart;
            $this->view->products = $products;
           
        }
    }
}