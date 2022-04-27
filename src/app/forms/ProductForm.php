<?php

namespace App\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Digit;


class ProductForm extends Form
{
    public function initialize()
    {
        $productname = new Text(
            'productname',
            [
                "class" => "form-control",
                "placeholder" => "Product name"
            ]
        );

        $productname->addValidator(
            new PresenceOf(["message" => "Productname required"])
        );

        $price = new Text(
            'price',
            [
                "class" => "form-control",
                "placeholder" => "Product price"
            ]
        );

        $price->addValidator(
            new PresenceOf(["message" => "Price required"])
        );

        $price->addValidator(
            new Digit(["message" => "Must be in integer"])
        );


        $category = new Text(
            'category',
            [
                "class" => "form-control",
                "placeholder" => "Category"
            ]
        );

        $category->addValidator(
            new PresenceOf(["message" => "Category required"])
        );

       
        $description = new TextArea(
            'description',
            [
                "class" => "form-control",
                "placeholder" => "DESCRIPTION OF THE PRODUCT"
            ]
        );

        $description->addValidator(
            new PresenceOf(["message" => "Description required"])
        );


        $this->add($productname);
        $this->add($price);
        $this->add($category);
        $this->add($description);
        
    }
}
