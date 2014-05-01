<?php
namespace App\Model\Entity;

/**
 * FbtContinent
 *
 * @Table(name="fbt_continent")
 * @Entity
 */
class Continent
{
    /**
     * @var integer
     *
     * @Column(name="idContinent", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $idcontinent;

    /**
     * @var string
     *
     * @Column(name="label", type="string", length=45, nullable=true)
     */
    private $label;

    public function getIdcontinent() {
        return $this->idcontinent;
    }

    public function getLabel() {
        return $this->label;
    }

    public function setIdcontinent($idcontinent) {
        $this->idcontinent = $idcontinent;
    }

    public function setLabel($label) {
        $this->label = $label;
    }


}
