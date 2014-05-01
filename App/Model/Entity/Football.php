<?php

namespace App\Model\Entity;

/**
 * FbtFootball
 *
 * @Table(name="fbt_football")
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
     * @OneToOne(targetEntity="FbtCountries")
     * @JoinColumns({
     *   @JoinColumn(name="Countries_id", referencedColumnName="idCountries")
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

    public function setCountries(\FbtCountries $countries) {
        $this->countries = $countries;
    }


}
