<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * FbtFootball
 *
 * @ORM\Table(name="fbt_football", uniqueConstraints={@ORM\UniqueConstraint(name="worldrank_UNIQUE", columns={"worldrank"})}, indexes={@ORM\Index(name="fk_FBTFootball_FBTCountries1", columns={"Countries_id"})})
 * @ORM\Entity
 */
class FbtFootball
{
    /**
     * @var integer
     *
     * @ORM\Column(name="worldrank", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $worldrank;

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
