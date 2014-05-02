<?php

namespace App\Model\Entity;

/**
 * FbtCountries
 *
 * @Table(name="FBT_countries")
 * @Entity
 */
class Countries
{
    /**
     * @var integer
     *
     * @Column(name="idCountries", type="smallint", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $idcountries;

    /**
     * @var integer
     *
     * @Column(name="iso_num", type="integer", nullable=false)
     */
    private $isoNum;

    /**
     * @var string
     *
     * @Column(name="iso_alpha2", type="string", length=2, nullable=false)
     */
    private $isoAlpha2;

    /**
     * @var string
     *
     * @Column(name="iso_alpha3", type="string", length=3, nullable=false)
     */
    private $isoAlpha3;

    /**
     * @var string
     *
     * @Column(name="name_fr", type="string", length=45, nullable=false)
     */
    private $nameFr;

    /**
     * @var string
     *
     * @Column(name="name_en", type="string", length=45, nullable=false)
     */
    private $nameEn;

    /**
     * @var \FbtContinent
     *
     * @ManyToOne(targetEntity="Continent")
     * @JoinColumns({
     *   @JoinColumn(name="idContinent", referencedColumnName="idContinent")
     * })
     */
    private $idcontinent;

    public function getIdcountries() {
        return $this->idcountries;
    }

    public function getIsoNum() {
        return $this->isoNum;
    }

    public function getIsoAlpha2() {
        return $this->isoAlpha2;
    }

    public function getIsoAlpha3() {
        return $this->isoAlpha3;
    }

    public function getNameFr() {
        return $this->nameFr;
    }

    public function getNameEn() {
        return $this->nameEn;
    }

    public function getIdcontinent() {
        return $this->idcontinent;
    }

    public function setIdcountries($idcountries) {
        $this->idcountries = $idcountries;
    }

    public function setIsoNum($isoNum) {
        $this->isoNum = $isoNum;
    }

    public function setIsoAlpha2($isoAlpha2) {
        $this->isoAlpha2 = $isoAlpha2;
    }

    public function setIsoAlpha3($isoAlpha3) {
        $this->isoAlpha3 = $isoAlpha3;
    }

    public function setNameFr($nameFr) {
        $this->nameFr = $nameFr;
    }

    public function setNameEn($nameEn) {
        $this->nameEn = $nameEn;
    }

    public function setIdcontinent(Continent $idcontinent) {
        $this->idcontinent = $idcontinent;
    }


}
