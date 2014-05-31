<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterType extends AbstractType {

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
        $builder->add('lastname', "text", array("constraints" => array(
            new Assert\NotBlank(),
            new Assert\Length(array('min' => 2))),
            "attr" => array("placeholder" => "nom", "class" => "form-control"))
        );
        $builder->add('firstname', "text", array("constraints" => array(
            new Assert\NotBlank(),
            new Assert\Length(array('min' => 2))),
            "attr" => array("placeholder" => "prénom", "class" => "form-control"))
        );
        $builder->add('password_repeated', 'repeated', array(
            'type' => 'password',
            'invalid_message' => 'The password fields must match.',
            'options' => array('required' => true, "attr" => array("placeholder" => "Password", "class" => "form-control")),
            'first_options' => array('label' => 'Password'),
            'second_options' => array('label' => 'Repeat Password')
            
        ));
    }

    public function getName() {
        return "register";
    }

}