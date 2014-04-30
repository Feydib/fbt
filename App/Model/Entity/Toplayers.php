<?php

namespace App\Model\Entity;
/**
 * FbtToplayers
 *
 * @Table(name="fbt_toplayers")
 * @Entity(repositoryClass="App\Model\Repository\ToPlayersRepository")
 */
class Toplayers
{
    /**
     * @var integer
     *
     * @Column(name="idToPlayers", type="integer", nullable=false)
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


}
