<?php

namespace App\Component;
use Phalcon\Escaper;


class MyEscaper 
{

    public $escaper;

    public function __construct()
    {
        $this->escaper = new Escaper();
    }

    public function sanitize($param)
    {
        return $this->escaper->escapeHtml($param);
    }
}
?>