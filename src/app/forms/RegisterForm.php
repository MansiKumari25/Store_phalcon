<?php

namespace App\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Digit;
use Phalcon\Validation\Validator\Between;

class RegisterForm extends Form
{
    public function initialize()
    {
        $username = new Text(
            'username',
            [
                "class" => "form-control",
                "placeholder" => "Enter user name"
            ]
        );

        $username->addValidator(
            new PresenceOf(["message" => "Username required"])
        );

        $fullname = new Text(
            'fullname',
            [
                "class" => "form-control",
                "placeholder" => "Enter full name"
            ]
        );

        $fullname->addValidator(
            new PresenceOf(["message" => "Fullname required"])
        );

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

        $password->addValidator(
            new StringLength(["min" => 5, "message" => "Password must be atleast of 5 characters"])
        );

        $password->addValidator(
            new Confirmation(["with" => "confirmpassword", "message" => "Password doesn't match"])
        );
        


        $confirmpassword = new Password(
            'confirmpassword',
            [
                "class" => "form-control",
                "placeholder" => "Confirm Password"
            ]
        );

        $confirmpassword->addValidator(
            new PresenceOf(["message" => "Password required"])
        );

       
        $mobile = new Text(
            'mobile',
            [
                "class" => "form-control",
                "placeholder" => "Enter Phone number"
            ]
        );

        $mobile->addValidator(
            new PresenceOf(["message" => "Phone number required"])
        );

        $mobile->addValidator(
            new Digit(["message" => "Phone number must be numeric"])
        );
        $mobile->addValidator(
            new Between([
                "minimum" => 1000000000,
                "maximum" => 9999999999,
                "message" => "Enter valid phone number"
            ])
        );

        $address = new TextArea(
            'address',
            [
                "class" => "form-control",
                "placeholder" => "Enter Address"
            ]
        );

        $address->addValidator(
            new PresenceOf(["message" => "Address required"])
        );

        $state = new Text(
            'state',
            [
                "class" => "form-control",
                "placeholder" => "Enter State"
            ]
        );

        $state->addValidator(
            new PresenceOf(["message" => "State required"])
        );


        $country = new Text(
            'country',
            [
                "class" => "form-control",
                "placeholder" => "Enter Country"
            ]
        );

        $country->addValidator(
            new PresenceOf(["message" => "Country name required"])
        );

        $this->add($username);
        $this->add($fullname);
        $this->add($email);
        $this->add($password);
        $this->add($confirmpassword);
        $this->add($mobile);
        $this->add($address);
        $this->add($country);
        $this->add($state);
    }
}
