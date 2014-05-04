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
    private $idtournplayers;

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
    private $isaccepted;

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
    

    public function getIdtournplayers() {
        return $this->idtournplayers;
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

    public function setIdtournplayers($idtournplayers) {
        $this->idtournplayers  = $idtournplayers;
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
        return $this->isaccepted;
    }

    public function setIsaccepted($isaccepted) {
        $this->isaccepted = $isaccepted;
    }



}
