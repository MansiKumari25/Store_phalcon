<?php

namespace App\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;


class LoginForm extends Form
{
    public function initialize()
    {
        $email = new Text(
            'email',
            [
                "class" => "form-control",
                "placeholder" => "Enter Email ID"
            ]
        );

        $email->addValidator(
            new PresenceOf(["message" => "Email required"])
        );

        $email->addValidator(
            new Email(["message" => "Enter valid email"])
        );

        $password = new Password(
            'password',
            [
                "class" => "form-control",
                "placeholder" => "Enter Password"
            ]
        );

        $password->addValidator(
            new PresenceOf(["message" => "Password required"])
        );       

       
        $this->add($email);
        $this->add($password);
       
    }
}
