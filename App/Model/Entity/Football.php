<?php

namespace App\Model\Entity;

/**
 * FbtFootball
 *
 * @Table(name="FBT_Football")
 * @Entity
 */
class Football
{
    /**
     * @var integer
     *
     * @Column(name="worldrank", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="NONE")
     */
    private $worldrank;

    /**
     * @var \FbtCountries
     *
     * @Id
     * @GeneratedValue(strategy="NONE")
     * @OneToOne(targetEntity="Countries")
     * @JoinColumns({
     *   @JoinColumn(name="idCountries", referencedColumnName="idCountries")
     * })
     */
    private $countries;

    public function getWorldrank() {
        return $this->worldrank;
    }

    public function getCountries() {
        return $this->countries;
    }

    public function setWorldrank($worldrank) {
        $this->worldrank = $worldrank;
    }

    public function setCountries(Countries $countries) {
        $this->countries = $countries;
    }


}
