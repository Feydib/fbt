<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class TournamentType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options ) {
        $builder->add('name', "text", array("constraints" => array(
            new Assert\NotBlank(),
            new Assert\Length(array('min' => 3))),
            "attr" => array("placeholder" => "name"))
        );
        /*$builder->add('date', "datetime", array("constraints" => array(
            new Assert\NotBlank()),
            "attr" => array("placeholder" => "date"))
        );*/
       
    }

    public function getName() {
        return "tournament";
    }

}