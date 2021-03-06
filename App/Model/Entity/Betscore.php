<?php


namespace App\Model\Entity;

/**
 * FbtBetscore
 *
 * @Table(name="FBT_BetScore")
 * @Entity(repositoryClass="App\Model\Repository\BetScoreRepository")
 */
class Betscore
{
    /**
     * @var integer
     *
     * @Column(name="idScore", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $idscore;

    /**
     * @var integer
     *
     * @Column(name="score", type="integer", nullable=true)
     */
    private $score;

    /**
     * @var \FbtLeague
     *
     * @ManyToOne(targetEntity="League")
     * @JoinColumns({
     *   @JoinColumn(name="idLeague", referencedColumnName="idLeague")
     * })
     */
    private $idleague;

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
     * @var \FbtPlayers
     *
     * @ManyToOne(targetEntity="Players")
     * @JoinColumns({
     *   @JoinColumn(name="idPlayers", referencedColumnName="idPlayers")
     * })
     */
    private $idplayers;

     /**
     *
     * @var players
     *
     *  @OneToMany(targetEntity="Tournplayers", mappedBy="idplayers", orphanRemoval=true)
     *
     */
    private $players;
    public function getPlayers() {
        return $this->players;
    }

    public function setPlayers(players $players) {
        $this->players = $players;
    }

        public function getIdscore() {
    	return $this->idscore;
    }

    public function getScore() {
    	return $this->score;
    }

    public function getIdleague() {
    	return $this->idleague;
    }

    public function getIdmatchs() {
    	return $this->idmatchs;
    }

    public function getIdplayers() {
    	return $this->idplayers;
    }

    public function setIdscore($idscore) {
    	$this->idscore = $idscore;
    }

    public function setScore($score) {
    	$this->score = $score;
    }

    public function setIdleague(League $idleague) {
    	$this->idleague = $idleague;
    }

    public function setIdmatchs(Matchs $idmatchs) {
    	$this->idmatchs = $idmatchs;
    }

    public function setIdplayers(Players $idplayers) {
    	$this->idplayers = $idplayers;
    }

}
