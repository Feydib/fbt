<?php
namespace App\Model\Entity;

/**
 * FbtLeagueType
 *
 * @Table(name="FBT_LeagueType")
 * @Entity
 */
class LeagueType
{
    /**
     * @var integer
     *
     * @Column(name="idLeagueType", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $idleaguetype;

    /**
     * @var string
     *
     * @Column(name="leagueType", type="string", length=45, nullable=true)
     */
    private $leaguetype;

    public function getIdleaguetype() {
        return $this->idleaguetype;
    }

    public function getLeaguetype() {
        return $this->leaguetype;
    }

    public function setIdleaguetype($idleaguetype) {
        $this->idleaguetype = $idleaguetype;
    }

    public function setLeaguetype($leaguetype) {
        $this->leaguetype = $leaguetype;
    }


}
