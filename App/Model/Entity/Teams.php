<?php
namespace App\Model\Entity;

/**
 * FbtTeams
 *
 * @Table(name="fbt_teams")
 * @Entity
 */
class Teams
{
    /**
     * @var integer
     *
     * @Column(name="idTeams", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="NONE")
     */
    private $idteams;

    /**
     * @var integer
     *
     * @Column(name="pool", type="integer", nullable=true)
     */
    private $pool;

    /**
     * @var integer
     *
     * @Column(name="ranking", type="integer", nullable=true)
     */
    private $ranking;

    /**
     * @var integer
     *
     * @Column(name="played", type="integer", nullable=true)
     */
    private $played;

    /**
     * @var integer
     *
     * @Column(name="win", type="integer", nullable=true)
     */
    private $win;

    /**
     * @var integer
     *
     * @Column(name="draw", type="integer", nullable=true)
     */
    private $draw;

    /**
     * @var integer
     *
     * @Column(name="lost", type="integer", nullable=true)
     */
    private $lost;

    /**
     * @var integer
     *
     * @Column(name="gf", type="integer", nullable=true)
     */
    private $gf;

    /**
     * @var integer
     *
     * @Column(name="ga", type="integer", nullable=true)
     */
    private $ga;

    /**
     * @var integer
     *
     * @Column(name="gav", type="integer", nullable=true)
     */
    private $gav;

    /**
     * @var integer
     *
     * @Column(name="pts", type="integer", nullable=true)
     */
    private $pts;

    /**
     * @var \FbtCountries
     * @OneToOne(targetEntity="Countries")
     * @JoinColumns({
     *   @JoinColumn(name="Countries_id", referencedColumnName="idCountries")
     * })
     */
    private $countries;

    public function getIdteams() {
        return $this->idteams;
    }

    public function getPool() {
        return $this->pool;
    }

    public function getRanking() {
        return $this->ranking;
    }

    public function getPlayed() {
        return $this->played;
    }

    public function getWin() {
        return $this->win;
    }

    public function getDraw() {
        return $this->draw;
    }

    public function getLost() {
        return $this->lost;
    }

    public function getGf() {
        return $this->gf;
    }

    public function getGa() {
        return $this->ga;
    }

    public function getGav() {
        return $this->gav;
    }

    public function getPts() {
        return $this->pts;
    }

    public function getCountries() {
        return $this->countries;
    }

    public function setIdteams($idteams) {
        $this->idteams = $idteams;
    }

    public function setPool($pool) {
        $this->pool = $pool;
    }

    public function setRanking($ranking) {
        $this->ranking = $ranking;
    }

    public function setPlayed($played) {
        $this->played = $played;
    }

    public function setWin($win) {
        $this->win = $win;
    }

    public function setDraw($draw) {
        $this->draw = $draw;
    }

    public function setLost($lost) {
        $this->lost = $lost;
    }

    public function setGf($gf) {
        $this->gf = $gf;
    }

    public function setGa($ga) {
        $this->ga = $ga;
    }

    public function setGav($gav) {
        $this->gav = $gav;
    }

    public function setPts($pts) {
        $this->pts = $pts;
    }

    public function setCountries(Countries $countries) {
        $this->countries = $countries;
    }


}
