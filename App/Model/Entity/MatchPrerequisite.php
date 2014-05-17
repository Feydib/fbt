<?php


namespace App\Model\Entity;

/**
 * FbtBetscore
 *
 * @Table(name="FBT_MatchPrerequisite")
 * @Entity(repositoryClass="App\Model\Repository\MatchPrerequisiteRepository")
 */
class MatchPrerequisite
{
    /**
     * @var integer
     *
     * @Column(name="idMatchPrerequisite", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $idmatchrerequisite;

    /**
     * @var \FbtMatchs
     *
     * @ManyToOne(targetEntity="Matchs")
     * @JoinColumns({
     *   @JoinColumn(name="idMatchs", referencedColumnName="idMatchs")
     * })
     */
    private $idmatchs;
    
    /**
     * @var \FbtMatchs
     *
     * @OneToOne(targetEntity="Matchs")
     * @JoinColumns({
     *   @JoinColumn(name="idMatchs1", referencedColumnName="idMatchs", nullable=true)
     * })
     */
    private $idmatchs1;
    
    /**
     * @var \FbtMatchs
     * @OneToOne(targetEntity="Matchs")
     * @JoinColumns({
     *   @JoinColumn(name="idMatchs2", referencedColumnName="idMatchs", nullable=true)
     * })
     */
    private $idmatchs2;

    /**
     * @var \FbtMatchs
     *
     * @Column(name="idPoolWinner", type="integer", nullable=true)
     */
    private $idpoolwinner;
    
    /**
     * @var \FbtMatchs
     *
     * @Column(name="idPoolSecond", type="integer", nullable=true)
     */
    private $idpoolsecond;

    public function getIdmatchrerequisite() {
        return $this->idmatchrerequisite;
    }

    public function getIdmatchs() {
        return $this->idmatchs;
    }

    public function getIdmatchs1() {
        return $this->idmatchs1;
    }

    public function getIdmatchs2() {
        return $this->idmatchs2;
    }

    public function getIdpoolwinner() {
        return $this->idpoolwinner;
    }

    public function getIdpoolsecond() {
        return $this->idpoolsecond;
    }

    public function setIdmatchrerequisite($idmatchrerequisite) {
        $this->idmatchrerequisite = $idmatchrerequisite;
    }

    public function setIdmatchs(\FbtMatchs $idmatchs) {
        $this->idmatchs = $idmatchs;
    }

    public function setIdmatchs1(\FbtMatchs $idmatchs1) {
        $this->idmatchs1 = $idmatchs1;
    }

    public function setIdmatchs2(\FbtMatchs $idmatchs2) {
        $this->idmatchs2 = $idmatchs2;
    }

    public function setIdpoolwinner(\FbtMatchs $idpoolwinner) {
        $this->idpoolwinner = $idpoolwinner;
    }

    public function setIdpoolsecond(\FbtMatchs $idpoolsecond) {
        $this->idpoolsecond = $idpoolsecond;
    }



}
