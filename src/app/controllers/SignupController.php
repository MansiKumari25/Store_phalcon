<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Request;
use App\Forms\RegisterForm;
use App\Component\MyEscaper;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\Stream;

class SignupController extends Controller
{

    public function indexAction()
    {
        $this->tag->setTitle('Sign Up');
        $this->view->form = new RegisterForm();
    }

    public function registerAction()
    {
        $user = new Users();
        $request = new Request();
        $form = new RegisterForm();
        $escaper = new MyEscaper();

        if(!$this->request->isPost()) {
            return $this->response->redirect("signup");
        }

        if(!$form->isValid($this->request->getPost())) {
            foreach($form->getMessages() as $message)
            {
                $this->flashSession->error($message);
                $this->dispatcher->forward(
                    [
                        "controller"=>"signup",
                        "action"=>"index"
                    ]
                );
                return;
            }
        }
        
        $inputs = array(
            "username" =>  $escaper->sanitize($request->get("username")),
            "fullname" =>  $escaper->sanitize($request->get("fullname")),
            "email" =>  $escaper->sanitize($request->get("email")),
            "password" =>  $escaper->sanitize($request->get("password")),
            "role" => "user",
            "status" => "pending",
            "phone" => $escaper->sanitize($request->get("mobile")),
            "address" =>  $escaper->sanitize($request->get("address")),
            "state" => $escaper->sanitize($request->get("state")),
            "country" => $escaper->sanitize($request->get("country")),
        );

        $user->assign(
            $inputs,
            [
                'username',
                'fullname',
                'email',
                'password',
                'role',
                'status',
                'phone',
                'address',
                'state',
                'country',
            ]
        );

        

        $success = $user->save();
        $this->view->success = $success;

        if($success){
            $this->view->message = "Registered succesfully";
        }else{
            $adapter = new Stream('../app/logs/signup.log');
            $logger  = new Logger(
                'messages',
                [
                    'main' => $adapter,
                ]
            );
            $err = $user->getMessages();
            foreach ($err as $e){
                $logger->error($e); 
            }

            if(!$user->save())
            {
                foreach($user->getMessages() as $m)
                {
                    $this->flashSession->error($m);
                    $this->dispatcher->forward([
                        "controller" => "signup",
                        "action" => "index"
                    ]);
                    return;
                }
            }
        }
    }
}
