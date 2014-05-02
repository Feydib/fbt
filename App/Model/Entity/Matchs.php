<?php

namespace App\Model\Entity;

/**
 * FbtMatchs
 *
 * @Table(name="FBT_Matchs")
 * @Entity(repositoryClass="App\Model\Repository\MatchRepository")
 */
class Matchs
{
    /**
     * @var integer
     *
     * @Column(name="idMatchs", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $idmatchs;

    /**
     * @var \DateTime
     *
     * @Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var string
     *
     * @Column(name="stadium", type="string", length=255, nullable=true)
     */
    private $stadium;
    
        /**
     * 
     * @var team
     *
     *  @OneToMany(targetEntity="Matchteam", mappedBy="idmatchs", orphanRemoval=true)
     *
     */
    private $teams;

    public function getIdmatchs() {
        return $this->idmatchs;
    }

    public function getDate() {
        return $this->date;
    }

    public function getStadium() {
        return $this->stadium;
    }

    public function setIdmatchs($idmatchs) {
        $this->idmatchs = $idmatchs;
    }

    public function setDate(\DateTime $date) {
        $this->date = $date;
    }

    public function setStadium($stadium) {
        $this->stadium = $stadium;
    }

    public function getTeams() {
        return $this->teams;
    }

    public function setTeams(Teams $teams) {
        $this->teams = $teams;
    }


}
