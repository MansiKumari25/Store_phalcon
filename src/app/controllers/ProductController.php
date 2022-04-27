<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Request;
use App\Forms\ProductForm;
use App\Component\MyEscaper;

class ProductController extends Controller
{
    public function addAction()
    {

        $product = new Products();
        $form = new ProductForm();
        $escaper = new MyEscaper();

        if(!$this->request->isPost()) {
            $this->flashSession->error("Invalid Authorization");
            $this->response->redirect("admin");
        } 
         
        if(!$form->isValid($this->request->getPost())) {
            foreach($form->getMessages() as $message)
            {
                $this->flashSession->error($message);
                $this->response->redirect("admin/allproduct");
                return;
            }
        }

        $inputs = array(
            "productname" =>  $escaper->sanitize($this->request->get("productname")),
            "price" =>  $escaper->sanitize($this->request->get("price")),
            "category" =>  $escaper->sanitize($this->request->get("category")),
            "description" =>  $escaper->sanitize($this->request->get("description")),
            "status" => "pending",
        );

        $product->assign(
            $inputs,
            [
                'productname',
                'price',
                'category',
                'description',
                'status',
            ]
        );

        if ($product->save()) {
            $this->flashSession->success("Product Added!!");
            $this->response->redirect("admin/allproduct");
        }else{
            foreach($product->getMessages() as $m)
            {
                $this->flashSession->error($m);
                $this->response->redirect("admin/allproduct");
                return;
            }
    }
}
}