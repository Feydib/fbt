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
     * @Column(name="idPoolTeam1", type="integer", nullable=true)
     */
    private $idpoolteam1;

    /**
     * @var \FbtMatchs
     *
     * @Column(name="idPoolTeam2", type="integer", nullable=true)
     */
    private $idpoolteam2;

    /**
     * @var integer
     *
     * @Column(name="rankTeam1", type="integer", nullable=true)
     */
    private $rankteam1;

    /**
     * @var integer
     *
     * @Column(name="rankTeam2", type="integer", nullable=true)
     */
    private $rankteam2;

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

    public function getIdpoolteam1() {
        return $this->idpoolteam1;
    }

    public function getIdpoolteam2() {
        return $this->idpoolteam2;
    }

    public function getRankteam1() {
        return $this->rankteam1;
    }

    public function getRankteam2() {
        return $this->rankteam2;
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

    public function setIdpoolteam1(\FbtMatchs $idpoolteam1) {
        $this->idpoolteam1 = $idpoolteam1;
    }

    public function setIdpoolteam2(\FbtMatchs $idpoolteam2) {
        $this->idpoolteam2 = $idpoolteam2;
    }

    public function setRankteam1($rankteam1) {
        $this->rankteam1 = $rankteam1;
    }

    public function setRankteam2($rankteam2) {
        $this->rankteam2 = $rankteam2;
    }


}
