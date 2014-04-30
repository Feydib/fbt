<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * FbtCountries
 *
 * @ORM\Table(name="fbt_countries", uniqueConstraints={@ORM\UniqueConstraint(name="idCountries_UNIQUE", columns={"idCountries"}), @ORM\UniqueConstraint(name="iso_num_UNIQUE", columns={"iso_num"}), @ORM\UniqueConstraint(name="iso_alpha2_UNIQUE", columns={"iso_alpha2"}), @ORM\UniqueConstraint(name="iso_alpha3_UNIQUE", columns={"iso_alpha3"})}, indexes={@ORM\Index(name="fk_FBT_Countries_FBT_Continent1", columns={"idContinent"})})
 * @ORM\Entity
 */
class FbtCountries
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idCountries", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcountries;

    /**
     * @var integer
     *
     * @ORM\Column(name="iso_num", type="integer", nullable=false)
     */
    private $isoNum;

    /**
     * @var string
     *
     * @ORM\Column(name="iso_alpha2", type="string", length=2, nullable=false)
     */
    private $isoAlpha2;

    /**
     * @var string
     *
     * @ORM\Column(name="iso_alpha3", type="string", length=3, nullable=false)
     */
    private $isoAlpha3;

    /**
     * @var string
     *
     * @ORM\Column(name="name_fr", type="string", length=45, nullable=false)
     */
    private $nameFr;

    /**
     * @var string
     *
     * @ORM\Column(name="name_en", type="string", length=45, nullable=false)
     */
    private $nameEn;

    /**
     * @var \FbtContinent
     *
     * @ORM\ManyToOne(targetEntity="FbtContinent")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idContinent", referencedColumnName="idContinent")
     * })
     */
    private $idcontinent;


}
