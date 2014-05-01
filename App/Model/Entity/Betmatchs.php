<?php

namespace App\Model\Entity;

/**
 * FbtBetmatchs
 *
 * @Table(name="fbt_betmatchs")
 * @Entity(repositoryClass="App\Model\Repository\BetMatchRepository")
 */
class Betmatchs
{
    /**
     * @var integer
     *
     * @Column(name="idBetMatchs", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $idbetmatchs;

    /**
     * @var integer
     *
     * @Column(name="score", type="integer", nullable=true)
     */
    private $score;

    /**
     * @var \FbtPlayers
     *
     * @OneToOne(targetEntity="Players")
     * @JoinColumns({
     *   @JoinColumn(name="idPlayers", referencedColumnName="idPlayers")
     * })
     */
    private $idplayers;

    /**
     * @var \FbtMatchteam
     *
     * @OneToOne(targetEntity="Matchteam")
     * @JoinColumns({
     *   @JoinColumn(name="idMatchTeam", referencedColumnName="idMatchTeam")
     * })
     */
    private $idmatchteam;

    public function getIdbetmatchs() {
        return $this->idbetmatchs;
    }

    public function getScore() {
        return $this->score;
    }

    public function getIdplayers() {
        return $this->idplayers;
    }

    public function getIdmatchteam() {
        return $this->idmatchteam;
    }

    public function setIdbetmatchs($idbetmatchs) {
        $this->idbetmatchs = $idbetmatchs;
    }

    public function setScore($score) {
        $this->score = $score;
    }

    public function setIdplayers(Players $idplayers) {
        $this->idplayers = $idplayers;
    }

    public function setIdmatchteam(Matchteam $idmatchteam) {
        $this->idmatchteam = $idmatchteam;
    }


}
