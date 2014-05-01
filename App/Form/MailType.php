<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class MailType extends AbstractType {
    function __construct($idTournament) {
        $this->idTournament = $idTournament;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options ) {
        $builder->add('email', "email", array("constraints" => array(
            new Assert\NotBlank(),
            new Assert\Email()),
            "attr" => array("placeholder" => "email"))
        );
        $builder->add('tournament', 'hidden', array(
            'data' =>  $this->idTournament,
        ));
       
    }

    public function getName() {
        return "mail";
    }

}