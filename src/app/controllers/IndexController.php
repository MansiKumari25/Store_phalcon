<?php

use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        $this->tag->setTitle('Home');
        $this->view->products= Products::find([
            'conditions' => 'status="approved"',
            'order'      => 'date desc'
        ]);
        
    }
}
