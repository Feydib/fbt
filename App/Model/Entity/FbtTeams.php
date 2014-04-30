<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * FbtTeams
 *
 * @ORM\Table(name="fbt_teams", indexes={@ORM\Index(name="fk_Teams_Countries", columns={"Countries_id"})})
 * @ORM\Entity
 */
class FbtTeams
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idTeams", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idteams;

    /**
     * @var integer
     *
     * @ORM\Column(name="pool", type="integer", nullable=true)
     */
    private $pool;

    /**
     * @var integer
     *
     * @ORM\Column(name="ranking", type="integer", nullable=true)
     */
    private $ranking;

    /**
     * @var integer
     *
     * @ORM\Column(name="played", type="integer", nullable=true)
     */
    private $played;

    /**
     * @var integer
     *
     * @ORM\Column(name="win", type="integer", nullable=true)
     */
    private $win;

    /**
     * @var integer
     *
     * @ORM\Column(name="draw", type="integer", nullable=true)
     */
    private $draw;

    /**
     * @var integer
     *
     * @ORM\Column(name="lost", type="integer", nullable=true)
     */
    private $lost;

    /**
     * @var integer
     *
     * @ORM\Column(name="gf", type="integer", nullable=true)
     */
    private $gf;

    /**
     * @var integer
     *
     * @ORM\Column(name="ga", type="integer", nullable=true)
     */
    private $ga;

    /**
     * @var integer
     *
     * @ORM\Column(name="gav", type="integer", nullable=true)
     */
    private $gav;

    /**
     * @var integer
     *
     * @ORM\Column(name="pts", type="integer", nullable=true)
     */
    private $pts;

    /**
     * @var \FbtCountries
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="FbtCountries")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Countries_id", referencedColumnName="idCountries")
     * })
     */
    private $countries;


}
