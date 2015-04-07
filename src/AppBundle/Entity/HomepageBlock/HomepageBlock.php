<?php
namespace AppBundle\Entity\HomepageBlock;

use       AppBundle\Service\Api\HomepageBlock\HomepageBlockServiceEntityInterface;
use       Doctrine\ORM\Mapping as ORM;

/**
 * HomepageBlocks
 *
 * @ORM\Table("homepageblok")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\HomepageBlock\HomepageBlockRepository")
 */
class HomepageBlock implements HomepageBlockServiceEntityInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="homepageblok_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var integer
     * 
     * @ORM\Column(name="tonen", type="smallint")
     */
    private $display;

    /**
     * @var integer
     *
     * @ORM\Column(name="wzt", type="smallint")
     */
    private $season;

    /**
     * @var array
     *
     * @ORM\Column(name="websites", type="simple_array")
     */
    private $websites;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="string", length=255)
     */
    private $link;

    /**
     * @var string
     *
     * @ORM\Column(name="titel", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="titel_en", type="string", length=255)
     */
    private $englishTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="titel_de", type="string", length=255)
     */
    private $germanTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="button", type="string", length=35)
     */
    private $button;

    /**
     * @var string
     *
     * @ORM\Column(name="button_en", type="string", length=35)
     */
    private $englishButton;

    /**
     * @var string
     *
     * @ORM\Column(name="button_de", type="string", length=35)
     */
    private $germanButton;

    /**
     * @var integer
     *
     * @ORM\Column(name="volgorde", type="integer")
     */
    private $rank;

    /**
     * @var integer
     *
     * @ORM\Column(name="positie", type="smallint")
     */
    private $position;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="begindatum", type="date")
     */
    private $publishedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="einddatum", type="date")
     */
    private $expiredAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="adddatetime", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="editdatetime", type="datetime")
     */
    private $updatedAt;


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
    public function setDisplay($display)
    {
        $this->display = $display;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getDisplay()
    {
        return $this->display;
    }

    /**
     * {@InheritDoc}
     */
    public function setSeason($season)
    {
        $this->season = $season;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getSeason()
    {
        return $this->season;
    }

    /**
     * {@InheritDoc}
     */
    public function setWebsites($websites)
    {
        $this->websites = $websites;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getWebsites()
    {
        return $this->websites;
    }

    /**
     * {@InheritDoc}
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * {@InheritDoc}
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * {@InheritDoc}
     */
    public function setEnglishTitle($englishTitle)
    {
        $this->englishTitle = $englishTitle;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getEnglishTitle()
    {
        return $this->englishTitle;
    }

    /**
     * {@InheritDoc}
     */
    public function setGermanTitle($germanTitle)
    {
        $this->germanTitle = $germanTitle;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getGermanTitle()
    {
        return $this->germanTitle;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setLocaleTitles($localeTitles)
    {
        // normalize locales
        $localeTitles = array_change_key_case($localeTitles);
        
        $this->setTitle(isset($localeTitles['nl']) ? $localeTitles['nl'] : '');
        $this->setEnglishTitle(isset($localeTitles['en']) ? $localeTitles['en'] : '');
        $this->setGermanTitle(isset($localeTitles['de']) ? $localeTitles['de'] : '');
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getLocaleTitle($locale, $html = [])
    {
        $locale    = strtolower($locale);
        $tag       = (isset($html['tag']) ? $html['tag'] : 'span');
        $attribute = false;
        $value     = false;
        
        if (isset($html['attribute']) && false !== $html['attribute']) {
            
            $attribute = (isset($html['attribute']['name'])  ? $html['attribute']['name']  : 'class');
            $value     = (isset($html['attribute']['value']) ? $html['attribute']['value'] : 'styled');
        }
        
        switch ($locale) {
            
            case 'en':
                $localeTitle = $this->getEnglishTitle();
                break;
                
            case 'de':
                $localeTitle = $this->getGermanTitle();
                break;
            
            case 'nl':
            default:
                $localeTitle = $this->getTitle();
                break;
        }
        
        // $html = false means disable format completely
        return (false === $html ? $localeTitle : (preg_replace('/\[font\](.+)\[\/font\]/i', '<' . $tag . (false !== $attribute ? (' ' . $attribute . '="' . $value . '"') : '') . '>$1</' . $tag .'>', $localeTitle)));
    }

    /**
     * {@InheritDoc}
     */
    public function setButton($button)
    {
        $this->button = $button;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getButton()
    {
        return $this->button;
    }

    /**
     * {@InheritDoc}
     */
    public function setEnglishButton($englishButton)
    {
        $this->englishButton = $englishButton;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getEnglishButton()
    {
        return $this->englishButton;
    }

    /**
     * {@InheritDoc}
     */
    public function setGermanButton($germanButton)
    {
        $this->germanButton = $germanButton;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getGermanButton()
    {
        return $this->germanButton;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setLocaleButtons($localeButtons)
    {
        // normalize locales
        $localeButtons = array_change_key_case($localeButtons);
        
        $this->setButton(isset($localeButtons['nl']) ? $localeButtons['nl'] : '');
        $this->setEnglishButton(isset($localeButtons['en']) ? $localeButtons['en'] : '');
        $this->setGermanButton(isset($localeButtons['de']) ? $localeButtons['de'] : '');
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getLocaleButton($locale)
    {
        $locale = strtolower($locale);
        switch ($locale) {
            
            case 'en':
                $localeButton = $this->getEnglishButton();
                break;
                
            case 'de':
                $localeButton = $this->getGermanButton();
                break;
            
            case 'nl':
            default:
                $localeButton = $this->getButton();
                break;
        }
        
        return $localeButton;
    }

    /**
     * {@InheritDoc}
     */
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * {@InheritDoc}
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * {@InheritDoc}
     */
    public function setPublishedAt($publishedAt)
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    /**
     * {@InheritDoc}
     */
    public function setExpiredAt($expiredAt)
    {
        $this->expiredAt = $expiredAt;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getExpiredAt()
    {
        return $this->expiredAt;
    }

    /**
     * {@InheritDoc}
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * {@InheritDoc}
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
