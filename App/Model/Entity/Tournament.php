<?php
namespace App\Model\Entity;

/**
 * FbtTournament
 *
 * @Table(name="FBT_Tournament")
 * @Entity(repositoryClass="App\Model\Repository\TournamentRepository")
 */
class Tournament
{
    /**
     * @var integer
     *
     * @Column(name="idTournament", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     * @OneToMany(targetEntity="Tournament" )
     */
    private $idtournament;

    /**
     * @var string
     *
     * @Column(name="name", type="string", length=45, nullable=true)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @Column(name="year", type="datetime", nullable=true)
     */
    private $year;

    /**
     *
     * @var players
     *
     *  @OneToMany(targetEntity="Tournplayers", mappedBy="idtournament", orphanRemoval=true)
     *
     */
    private $players;

    /**
     * @var \FbtLeague
     * @OneToOne(targetEntity="League")
     * @JoinColumns({
     *   @JoinColumn(name="idLeague", referencedColumnName="idLeague")
     * })
     */
    private $idleague;

    public function getIdtournament() {
        return $this->idtournament;
    }

    public function getIdleague() {
        return $this->idleague;
    }

    public function getName() {
        return $this->name;
    }

    public function getYear() {
        return $this->year;
    }

    public function setIdtournament($idtournament) {
        $this->idtournament = $idtournament;
    }

    public function setIdleague(League $idleague) {
        $this->idleague = $idleague;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setYear(\DateTime $year) {
        $this->year = $year;
    }

    public function getPlayers() {
        return $this->players;
    }

    public function setPlayers(players $players) {
        $this->players = $players;
    }


}
