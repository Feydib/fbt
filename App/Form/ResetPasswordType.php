<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ResetPasswordType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options ) {
    	$builder->add('username', "text", array("constraints" => array(
    			new Assert\NotBlank(),
    			new Assert\Length(array('min' => 3))),
    			"attr" => array("placeholder" => "pseudo", "class" => "form-control"))
    	);
        $builder->add('email', "email", array("constraints" => array(
            new Assert\NotBlank(),
            new Assert\Email()),
            "attr" => array("placeholder" => "email", "class" => "form-control"))
        ); 
    }

    public function getName() {
        return "mail";
    }

}