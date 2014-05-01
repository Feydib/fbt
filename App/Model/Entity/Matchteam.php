<?php

namespace App\Model\Entity;

/**
 * FbtMatchteam
 *
 * @Table(name="fbt_matchteam", uniqueConstraints={@UniqueConstraint(name="id_UNIQUE", columns={"idMatchTeam"})}, indexes={@Index(name="fk_FBT_MatchTeam_FBTTeams1", columns={"idTeams"}), @Index(name="fk_FBT_MatchTeam_FBTMatchs1", columns={"idMatchs"})})
 * @Entity
 */
class Matchteam
{
    /**
     * @var integer
     *
     * @Column(name="idMatchTeam", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $idmatchteam;

    /**
     * @var integer
     *
     * @Column(name="score", type="integer", nullable=true)
     */
    private $score;

    /**
     * @var integer
     *
     * @Column(name="pen", type="integer", nullable=true)
     */
    private $pen;

    /**
     * @var \FbtTeams
     *
     * @ManyToOne(targetEntity="Teams")
     * @JoinColumns({
     *   @JoinColumn(name="idTeams", referencedColumnName="idTeams")
     * })
     */
    private $idteams;

    /**
     * @var \FbtMatchs
     *
     * @ManyToOne(targetEntity="Matchs")
     * @JoinColumns({
     *   @JoinColumn(name="idMatchs", referencedColumnName="idMatchs")
     * })
     */
    private $idmatchs;

    public function getIdmatchteam() {
        return $this->idmatchteam;
    }

    public function getScore() {
        return $this->score;
    }

    public function getPen() {
        return $this->pen;
    }

    public function getIdteams() {
        return $this->idteams;
    }

    public function getIdmatchs() {
        return $this->idmatchs;
    }

    public function setIdmatchteam($idmatchteam) {
        $this->idmatchteam = $idmatchteam;
    }

    public function setScore($score) {
        $this->score = $score;
    }

    public function setPen($pen) {
        $this->pen = $pen;
    }

    public function setIdteams(Teams $idteams) {
        $this->idteams = $idteams;
    }

    public function setIdmatchs(Matchs $idmatchs) {
        $this->idmatchs = $idmatchs;
    }


}
