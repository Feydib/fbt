<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * FbtNews
 *
 * @ORM\Table(name="fbt_news")
 * @ORM\Entity
 */
class FbtNews
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idNews", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idnews;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;


}
