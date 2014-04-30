<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * FbtMatchteam
 *
 * @ORM\Table(name="fbt_matchteam", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"idMatchTeam"})}, indexes={@ORM\Index(name="fk_FBT_MatchTeam_FBTTeams1", columns={"idTeams"}), @ORM\Index(name="fk_FBT_MatchTeam_FBTMatchs1", columns={"idMatchs"})})
 * @ORM\Entity
 */
class FbtMatchteam
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idMatchTeam", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idmatchteam;

    /**
     * @var integer
     *
     * @ORM\Column(name="score", type="integer", nullable=true)
     */
    private $score;

    /**
     * @var integer
     *
     * @ORM\Column(name="pen", type="integer", nullable=true)
     */
    private $pen;

    /**
     * @var \FbtTeams
     *
     * @ORM\ManyToOne(targetEntity="FbtTeams")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idTeams", referencedColumnName="idTeams")
     * })
     */
    private $idteams;

    /**
     * @var \FbtMatchs
     *
     * @ORM\ManyToOne(targetEntity="FbtMatchs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idMatchs", referencedColumnName="idMatchs")
     * })
     */
    private $idmatchs;


}
