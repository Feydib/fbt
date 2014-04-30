<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * FbtMatchs
 *
 * @ORM\Table(name="fbt_matchs")
 * @ORM\Entity
 */
class FbtMatchs
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idMatchs", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idmatchs;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="stadium", type="string", length=255, nullable=true)
     */
    private $stadium;


}
