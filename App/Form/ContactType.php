<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ContactType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options ) {

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
        $builder->add('comments', "textarea", array("constraints" => array(
            new Assert\NotBlank(),
            new Assert\Length(array('min' => 2))),
            "attr" => array("placeholder" => "Commentaires", "class" => "form-control"))
        );
    }

    public function getName() {
        return "contact";
    }

}