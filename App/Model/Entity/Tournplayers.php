<?php

namespace App\Model\Entity;
/**
 * FbtTournplayers
 *
 * @Table(name="FBT_TournPlayers")
 * @Entity(repositoryClass="App\Model\Repository\TournPlayersRepository")
 */
class Tournplayers
{
    /**
     * @var integer
     *
     * @Column(name="idTournPlayers", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $idtoplayers;

    /**
     * @var boolean
     *
     * @Column(name="isAdmin", type="boolean", nullable=true)
     */
    private $isadmin;
    
    /**
     * @var boolean
     *
     * @Column(name="isAccepted", type="boolean", nullable=true)
     */
    private $isAccepted;

    /**
     * @var \FbtTournament
     *
     * @ManyToOne(targetEntity="Tournament" )
     * @JoinColumns({
     *   @JoinColumn(name="idTournament", referencedColumnName="idTournament")
     * })
     */
    private $idtournament;

    /**
     * @var \FbtPlayers
     *
     * @ManyToOne(targetEntity="Players" )
     * @JoinColumns({
     *   @JoinColumn(name="idPlayers", referencedColumnName="idPlayers")
     * })
     */
    private $idplayers;
    

    public function getIdtoplayers() {
        return $this->idtoplayers;
    }

    public function getIsadmin() {
        return $this->isadmin;
    }

    public function getIdtournament() {
        return $this->idtournament;
    }

    public function getIdplayers() {
        return $this->idplayers;
    }

    public function setIdtoplayers($idtoplayers) {
        $this->idtoplayers = $idtoplayers;
    }

    public function setIsadmin($isadmin) {
        $this->isadmin = $isadmin;
    }

    public function setIdtournament(Tournament $idtournament) {
        $this->idtournament = $idtournament;
    }

    public function setIdplayers(Players $idplayers) {
        $this->idplayers = $idplayers;
    }
    public function getIsaccepted() {
        return $this->isAccepted;
    }

    public function setIsaccepted($isaccepted) {
        $this->isAccepted = $isaccepted;
    }



}
