<?php

namespace App\Model\Entity;
/**
 * FbtLeague
 *
 * @Table(name="FBT_League")
 * @Entity(repositoryClass="App\Model\Repository\LeagueRepository")
 */
class League
{
    /**
     * @var integer
     *
     * @Column(name="idLeague", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $idleague;

     /**
     * @var string
     *
     * @Column(name="leagueName", type="string", length=45, nullable=true)
     */
    private $leaguename;

    /**
     * @var \FbtLeagueType
     *
     * @ManyToOne(targetEntity="LeagueType" )
     * @JoinColumns({
     *   @JoinColumn(name="LeagueType_id", referencedColumnName="idLeagueType")
     * })
     */
    private $idleaguetype;

    public function getIdleague() {
        return $this->idleague;
    }

    public function getLeaguename() {
        return $this->leaguename;
    }

    public function getIdleaguetype() {
        return $this->idleaguetype;
    }

    public function setIdleague($idleague) {
        $this->idleague  = $idleague;
    }

    public function setLeaguename($leaguename) {
        $this->leaguename = $leaguename;
    }

    public function setIdLeaguetype(LeagueType $idleaguetype) {
        $this->idleaguetype = $idleaguetype;
    }

}
