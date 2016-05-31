<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterType extends AbstractType {

    function __construct($user =null) {
        $this->user = $user;
    }

    public function buildForm(FormBuilderInterface $builder, array $options ) {
        if( $this->user == null) {
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
        } else {
            $builder->add('id', "hidden", array("constraints" => array(
                new Assert\NotBlank()),
                "attr" => array("placeholder" => "pseudo", "class" => "form-control"),
                "data" => $this->user->getIdplayers())
            );

            $builder->add('username', "text", array("constraints" => array(
            	new Assert\NotBlank(),
            	new Assert\Length(array('min' => 3))),
            	"attr" => array("placeholder" => "pseudo", "class" => "form-control"),
            	"data" => $this->user->getUsername())
			);

            $builder->add('email', "email", array("constraints" => array(
                new Assert\NotBlank(),
                new Assert\Email()),
                "attr" => array("placeholder" => "email", "class" => "form-control"),
                "data" => $this->user->getMail())
            );

            $builder->add('lastname', "text", array("constraints" => array(
                new Assert\NotBlank(),
                new Assert\Length(array('min' => 2))),
                "attr" => array("placeholder" => "nom", "class" => "form-control"),
                "data" => $this->user->getLastname())
            );

            $builder->add('firstname', "text", array("constraints" => array(
                new Assert\NotBlank(),
                new Assert\Length(array('min' => 2))),
                "attr" => array("placeholder" => "prénom", "class" => "form-control"),
                "data" => $this->user->getFirstname())
            );
            $builder->add('password_repeated', 'repeated', array(
                'type' => 'password',
                'required' => false,
                'invalid_message' => 'The password fields must match.',
                'options' => array("attr" => array("placeholder" => "Password", "class" => "form-control")),
                'first_options' => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
                "data" => $this->user->getPassword()
            ));
        }

    }

    public function getName() {
        return "register";
    }

}