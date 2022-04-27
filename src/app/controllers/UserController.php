<?php

use Phalcon\Mvc\Controller;
use App\Component\MyEscaper;

class UserController extends Controller
{

    public function indexAction()
    {
        //print_r($this->cookies->get('my_cookie'));
        if($this->session->get("user")==null) {
            $this->response->redirect("login");
        } else {
            $user = $this->session->get("user");
            $orders = Orders::find("user_id='$user->user_id'");
            $this->view->orders = $orders;
        }
    }

    public function updateprofileAction()
    {
        $escaper = new MyEscaper();
        $id = $this->request->get("id");
        $user = Users::findFirst($id);
        $user->username = $escaper->sanitize($this->request->get("username"));
        $user->fullname = $escaper->sanitize($this->request->get("fullname"));
        $user->email = $escaper->sanitize($this->request->get("email"));
        $user->phone = $escaper->sanitize($this->request->get("phone"));
        $user->address = $escaper->sanitize($this->request->get("address"));
        $user->state =  $escaper->sanitize($this->request->get("state"));
        $user->country = $escaper->sanitize($this->request->get("country"));
        
        if($user->update()) {
            $this->flashSession->success("Profile Updated");
            $this->response->redirect("user");

        } else {
            foreach($user->getMessages() as $m)
                {
                    $this->flashSession->error($m);
                    $this->response->redirect("user");
                    return;
                }
        }

    }

    public function addtoCartAction()
    {
        if($this->session->get("user") == null) {
                $this->response->redirect("login");
        } else {
            $pid = $this->request->get("pid");
            $user= $this->session->get("user");

           $cart = Cart::findFirst("user_id='$user->user_id' AND product_id='$pid'");

           if($cart == null) {
               $cart = new Cart();
               $cart->assign(
                   [
                       "user_id" => $user->user_id,
                       "product_id" => $pid,
                       "quantity" => 1
                   ],
                   [
                       'user_id',
                       'product_id',
                       'quantity'
                   ]
                   );
                  if ($cart->save()) {
                    $this->flashSession->success("Item added in cart!!");
                    $this->response->redirect("/");
                  } else {
                    foreach($cart->getMessages() as $m)
                    {
                        $this->flashSession->error($m);
                        $this->response->redirect("/");
                        return;
                    }
                  }
           } else {
               $cart->quantity = $cart->quantity+1;
               if ($cart->update()) {
                    $this->flashSession->success("Cart updated!!");
                    $this->response->redirect("/");
               } else {
                foreach($cart->getMessages() as $m)
                {
                    $this->flashSession->error($m);
                    $this->response->redirect("/");
                    return;
                }
               }
           }
                
        }
    }
}

