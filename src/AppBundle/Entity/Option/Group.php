<?php
namespace AppBundle\Entity\Option;
use       AppBundle\Service\Api\Option\GroupEntityInterface;
use       Doctrine\ORM\Mapping as ORM;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
/**
 * Option group
 *
 * @ORM\Table(name="optie_groep")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Option\OptionRepository")
 */
class Group implements GroupEntityInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="optie_groep_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var KindEntityInterface
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Option\Kind")
     * @ORM\JoinColumn(name="optie_soort_id", referencedColumnName="optie_soort_id")
     */
    private $kind;

    /**
     * @var KindEntityInterface
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Option\Section", mappedBy="group")
     */
    private $sections;

    /**
     * @var string
     *
     * @ORM\Column(name="naam", type="string", length=50)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="duur", type="integer")
     */
    private $duration;

    /**
     * @var string
     *
     * @ORM\Column(name="omschrijving", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="omschrijving_en", type="text")
     */
    private $englishDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="omschrijving_de", type="text")
     */
    private $germanDescription;


    /**
     * {@InheritDoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@InheritDoc}
     */
    public function setKind($kind)
    {
        $this->kind = $kind;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getKind()
    {
        return $this->kind;
    }

    /**
     * {@InheritDoc}
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@InheritDoc}
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * {@InheritDoc}
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * {@InheritDoc}
     */
    public function setEnglishDescription($englishDescription)
    {
        $this->englishDescription = $englishDescription;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getEnglishDescription()
    {
        return $this->englishDescription;
    }

    /**
     * {@InheritDoc}
     */
    public function setGermanDescription($germanDescription)
    {
        $this->germanDescription = $germanDescription;
    }

    /**
     * {@InheritDoc}
     */
    public function getGermanDescription()
    {
        return $this->germanDescription;
    }

    /**
     * {@InheritDoc}
     */
    public function setLocaleDescriptions($localeDescriptions)
    {
        // normalize locales
        $localeDescriptions = array_change_key_case($localeDescriptions);

        $this->setDescription(isset($localeDescriptions['nl']) ? $localeDescriptions['nl'] : '');
        $this->setEnglishDescription(isset($localeDescriptions['en']) ? $localeDescriptions['en'] : '');
        $this->setGermanDescription(isset($localeDescriptions['de']) ? $localeDescriptions['de'] : '');

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getLocaleDescription($locale)
    {
        $locale = strtolower($locale);
        switch ($locale) {

            case 'en':
                $localeDescription = $this->getEnglishDescription();
                break;

            case 'de':
                $localeDescription = $this->getGermanDescription();
                break;

            case 'nl':
            default:
                $localeDescription = $this->getDescription();
                break;
        }

        return $localeDescription;
    }
}