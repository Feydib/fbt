<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * FbtBetmatchs
 *
 * @ORM\Table(name="fbt_betmatchs", indexes={@ORM\Index(name="fk_BetMatchs_Players1", columns={"idPlayers"}), @ORM\Index(name="fk_FBTBetMatchs_FBT_MatchTeam1", columns={"idMatchTeam"})})
 * @ORM\Entity
 */
class FbtBetmatchs
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idBetMatchs", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idbetmatchs;

    /**
     * @var integer
     *
     * @ORM\Column(name="score", type="integer", nullable=true)
     */
    private $score;

    /**
     * @var \FbtPlayers
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="FbtPlayers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idPlayers", referencedColumnName="idPlayers")
     * })
     */
    private $idplayers;

    /**
     * @var \FbtMatchteam
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="FbtMatchteam")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idMatchTeam", referencedColumnName="idMatchTeam")
     * })
     */
    private $idmatchteam;


}
