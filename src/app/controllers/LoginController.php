<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Request;
use App\Forms\LoginForm;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\Stream;
use App\Component\MyEscaper;

class LoginController extends Controller
{

    public function indexAction()
    {
        $this->tag->setTitle('Login');
        $this->view->form = new LoginForm();
        if ($this->cookies->has('login')) {
            $loginCookie = $this->cookies->get('login');
            $email = $loginCookie->getValue();
            $user = Users::findFirst("email='$email'");
            $this->session->set("user", $user);
            $this->response->redirect('/user');
        } else {
            $this->view->email = "";
        }
        
    }

    public function loginAction()
    {
        $user = new Users();
        $request = new Request();
        $form= new LoginForm();
        $escaper = new MyEscaper();
        $email = $escaper->sanitize($request->get("email"));
        $password = $escaper->sanitize($request->get("password"));

        if(!$this->request->isPost()) {
            return $this->response->redirect("login");
        }

        if(!$form->isValid($this->request->getPost())) {
            foreach($form->getMessages() as $message)
            {
                $this->flashSession->error($message);
                $this->dispatcher->forward(
                    [
                        "controller"=>"login",
                        "action"=>"index"
                    ]
                );
                return;
            }
        }

        $remember =  $request->get("remember"); 

        $user = Users::findFirst("email='$email' AND password='$password' AND role='user' AND status='approved'");
        if($user !="" && $remember=="remember")
        {
            if (!$this->cookies->has('login')) {
                $this->cookies->set(
                    'login',
                    $email,
                    time() + 15 * 86400
                );
                $this->cookies->send(); 
            }
            $this->session->set("user", $user);
            $this->response->redirect('/user');
        } else if($user!="" && $remember != "remember") {
            $this->session->set("user", $user);
            $this->response->redirect('/user');
        }
        else {
            $this->flashSession->error("User Doesn't Exist or Your request is still pending");
            $this->dispatcher->forward([
                "controller" => "login",
                "action" => "index"
            ]);
                
        }
    }

    public function logoutAction()
    {
        $this->cookies->get('login')->delete();
        $this->session->remove('user');
         $this->session->destroy();
         $this->response->redirect('login');
    }
}
