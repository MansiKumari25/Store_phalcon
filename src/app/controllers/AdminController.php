<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Request;
use App\Forms\LoginForm;
use App\Component\MyEscaper;
use App\Forms\ProductForm;

class AdminController extends Controller
{

    public function indexAction()
    {
        $this->tag->setTitle('Admin Login');
        $this->view->form = new LoginForm(); 
    }

    public function dashboardAction()
    {
        /**
         * If admin is already logged in
         */
        if (!is_null($this->session->get("admin"))) {
            $this->view->users= Users::find([
                'conditions' => 'role="user"',
                'order'      => 'reg_date desc limit 5'
            ]);
            $this->tag->setTitle("Admin Dashboard");

        } else {

            /**
             * If admin in not logged in
             */

            $request = new Request();
            $form= new LoginForm();
            $escaper = new MyEscaper();
    
            //sanitizing data
            $email = $escaper->sanitize($request->get("email"));
            $password = $escaper->sanitize($request->get("password"));
    
            if(!$this->request->isPost()) {
                return $this->response->redirect("admin");
            } 
    
            if(!$form->isValid($this->request->getPost())) {
                foreach($form->getMessages() as $message)
                {
                    $this->flashSession->error($message);
                    $this->dispatcher->forward(
                        [
                            "controller"=>"admin",
                            "action"=>"index"
                        ]
                    );
                    return;
                }
            }
    
            $admin = Users::findFirst("email='$email' AND password='$password' AND role='admin' AND status='approved'");
            if($admin != "")
            {
                $this->session->set("admin", $admin);
                $this->view->users= Users::find([
                    'conditions' => 'role="user"',
                    'order'      => 'reg_date desc limit 5'
                ]);
                $this->tag->setTitle("Admin Dashboard");
                
            } else { 
                $this->flashSession->error("Admin Doesn't Exist");
                $this->dispatcher->forward([
                    "controller" => "admin",
                    "action" => "index"
                ]);
                    
            }
        } 
    }

    public function allproductAction()
    {
        $this->view->form = new ProductForm();
        $this->tag->setTitle("Admin Dashboard - All Products");
        $this->view->products= Products::find();
    }

    public function logoutAction()
    {
        $this->session->remove('admin');
         $this->session->destroy();
         $this->response->redirect('admin');
    }

    public function allcustomersAction()
    {
        $this->view->users= Users::find([
            'conditions' => 'role="user"',
            'order'      => 'reg_date desc limit 5'
        ]);
        $this->tag->setTitle("Customers");
    }

    public function deleteuserAction()
    {
        $id= $this->request->get("id");
        if (Users::findFirst($id)->delete()) {
            $this->flashSession->success("User deleted");
            $this->response->redirect("admin/allcustomers");
        } else{
            $this->flashSession->error("Unable to delete");
            $this->response->redirect("admin/allcustomers");
        }
    }

    public function userStatusAction()
    {
        $status=$this->request->get("status");
        $id=$this->request->get("id");
        $user = Users::findFirst($id);
        $user->status = $status;
        if($user->update()) {
            $this->flashSession->success("Status Changed");
            $this->response->redirect("admin/allcustomers");
        } else{
            $this->flashSession->error("Unable to change status");
        $this->response->redirect("admin/allcustomers");
        }
        
    }

    public function deleteproductAction()
    {
        $sku= $this->request->get("sku");
        if (Products::findFirst($sku)->delete()) {
            $this->flashSession->success("Product deleted");
            $this->response->redirect("admin/allproduct");
        } else{
            $this->flashSession->error("Unable to delete");
            $this->response->redirect("admin/allproduct");
        }
    }

    public function productStatusAction()
    {
        $status=$this->request->get("status");
        $sku=$this->request->get("sku");
        $product = Products::findFirst($sku);
        $product->status = $status;
        if($product->update()) {
            $this->flashSession->success("Status Changed");
            $this->response->redirect("admin/allproduct");
        } else{
            $this->flashSession->error("Unable to change status");
        $this->response->redirect("admin/allproduct");
        }
        
    }
}
