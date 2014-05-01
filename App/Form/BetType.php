<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class BetType extends AbstractType {
    function __construct($idMatchTeam =null, $data = null) {
        $this->matchTeam = $idMatchTeam;
        $this->data = $data;
    }
    
    /**
     * get possible score 
     * @param type $maxScore
     * @return type
     */
    private function getScore($maxScore) {
        $array = array();
        for($i = 0 ; $i <= $maxScore ; $i++) {
            $array[$i] = $i;
        }
        return $array;
    }
  
    
    public function buildForm(FormBuilderInterface $builder, array $options ) {
        if (is_array($this->matchTeam)) {
            foreach ($this->matchTeam as $idMatchTeam) {
                $builder->add('score'.$idMatchTeam, 'choice', array(
                    'label' => ' ',
                    'choices' => $this->getScore(10),
                    'expanded' => false,
                ));
            }
        } else {
            $builder->add('score'.$this->matchTeam, 'choice', array(
                'label' => ' ',
                'choices' => $this->getScore(10),
                'expanded' => false,
                'data' => $this->data,
            ));
        }
    }

    public function getName() {
        return "bet";
    }

}