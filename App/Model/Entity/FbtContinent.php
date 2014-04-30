<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * FbtContinent
 *
 * @ORM\Table(name="fbt_continent", uniqueConstraints={@ORM\UniqueConstraint(name="idContinent_UNIQUE", columns={"idContinent"})})
 * @ORM\Entity
 */
class FbtContinent
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idContinent", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcontinent;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=45, nullable=true)
     */
    private $label;


}
