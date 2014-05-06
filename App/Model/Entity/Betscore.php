<?php


namespace App\Model\Entity;

/**
 * FbtBetscore
 *
 * @Table(name="FBT_BetScore")
 * @Entity(repositoryClass="App\Model\Repository\BetScoreRepository")
 */
class FbtBetscore
{
    /**
     * @var integer
     *
     * Column(name="idScore", type="integer", nullable=false)
     * Id
     * GeneratedValue(strategy="IDENTITY")
     */
    private $idscore;

    /**
     * @var integer
     *
     * Column(name="score", type="integer", nullable=true)
     */
    private $score;

    /**
     * @var \FbtMatchs
     *
     * ManyToOne(targetEntity="FbtMatchs")
     * JoinColumns({
     *   JoinColumn(name="idMatchs", referencedColumnName="idMatchs")
     * })
     */
    private $idmatchs;

    /**
     * @var \FbtPlayers
     *
     * ManyToOne(targetEntity="FbtPlayers")
     * JoinColumns({
     *   JoinColumn(name="idPlayers", referencedColumnName="idPlayers")
     * })
     */
    private $idplayers;


}
