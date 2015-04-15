<?php
namespace AppBundle\Entity\Booking\Survey;

use       AppBundle\Service\Api\Booking\BookingServiceEntityInterface;
use       AppBundle\Service\Api\Booking\Survey\SurveyServiceEntityInterface;
use       Doctrine\ORM\Mapping as ORM;
/**
 * Survey
 *
 * @ORM\Table(name="boeking_enquete")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Booking\Survey\SurveyRepository")
 */
class Survey implements SurveyServiceEntityInterface
{
    /**
     * @var BookingServiceEntityInterface
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Booking\Booking", inversedBy="surveys")
     * @ORM\Id
     * @ORM\JoinColumn(name="boeking_id", referencedColumnName="boeking_id")
     */
    private $booking;

    /**
     * @var string
     *
     * @ORM\Column(name="websitetekst", type="text")
     */
    private $websiteText;

    /**
     * @var string
     *
     * @ORM\Column(name="websitetekst_gewijzigd", type="text")
     */
    private $websiteTextModified;

    /**
     * @var string
     *
     * @ORM\Column(name="websitetekst_gewijzigd_en", type="text")
     */
    private $websiteTextModifiedEnglish;

    /**
     * @var string
     *
     * @ORM\Column(name="websitetekst_gewijzigd_de", type="text")
     */
    private $websiteTextModifiedGerman;

    /**
     * @var string
     *
     * @ORM\Column(name="websitetekst_gewijzigd_nl", type="text")
     */
    private $websiteTextModifiedDutch;

    /**
     * @var string
     *
     * @ORM\Column(name="tekst_language", type="string", length=2)
     */
    private $language;

    /**
     * @var string
     *
     * @ORM\Column(name="websitetekst_naam", type="string", length=255)
     */
    private $websiteName;

    /**
     * @var integer
     *
     * @ORM\Column(name="beoordeeld", type="smallint")
     */
    private $reviewed;

    /**
     * @var integer
     *
     * @ORM\Column(name="type_id", type="integer")
     */
    private $typeId;

    /**
     * @var TypeServiceEntityInterface
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Type\Type", inversedBy="surveys")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="type_id")
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="aankomstdatum_exact", type="date")
     */
    private $arrivalAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="vertrekdatum_exact", type="date")
     */
    private $departureAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="invulmoment", type="datetime")
     */
    private $filledInAt;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="vraag1_1", type="integer")
     */
    private $question_1_1;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="vraag1_2", type="integer")
     */
    private $question_1_2;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="vraag1_3", type="integer")
     */
    private $question_1_3;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="vraag1_4", type="integer")
     */
    private $question_1_4;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="vraag1_5", type="integer")
     */
    private $question_1_5;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="vraag1_6", type="integer")
     */
    private $question_1_6;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="vraag1_7", type="integer")
     */
    private $question_1_7;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="vraag1_toelichting", type="integer")
     */
    private $question_1_explanation;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="vraag2_1", type="integer")
     */
    private $question_2_1;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="vraag2_2", type="integer")
     */
    private $question_2_2;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="vraag2_3", type="integer")
     */
    private $question_2_3;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="vraag2_4", type="integer")
     */
    private $question_2_4;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="vraag2_5", type="integer")
     */
    private $question_2_5;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="vraag2_6", type="integer")
     */
    private $question_2_6;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="vraag2_7", type="integer")
     */
    private $question_2_7;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="vraag2_anders", type="integer")
     */
    private $question_2_other;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="vraag3_1", type="integer")
     */
    private $question_3_1;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="vraag3_2", type="integer")
     */
    private $question_3_2;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="vraag3_3", type="integer")
     */
    private $question_3_3;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="vraag3_4", type="integer")
     */
    private $question_3_4;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="vraag3_5", type="integer")
     */
    private $question_3_5;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="vraag3_6", type="integer")
     */
    private $question_3_6;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="vraag3_7", type="integer")
     */
    private $question_3_7;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="vraag3_8", type="integer")
     */
    private $question_3_8;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="vraag3_9", type="integer")
     */
    private $question_3_9;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="vraag3_toelichting", type="integer")
     */
    private $question_3_explanation;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="vraag4", type="integer")
     */
    private $question_4;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="vraag5", type="integer")
     */
    private $question_5;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="vraag5_toelichting", type="integer")
     */
    private $question_5_explanation;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="vraag6", type="integer")
     */
    private $question_6;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="vraag7", type="integer")
     */
    private $question_7;
    
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
    public function setBooking($booking)
    {
        $this->booking = $booking;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getBooking()
    {
        return $this->booking;
    }

    /**
     * {@InheritDoc}
     */
    public function setWebsiteText($websiteText)
    {
        $this->websiteText = $websiteText;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getWebsiteText()
    {
        return $this->websiteText;
    }

    /**
     * {@InheritDoc}
     */
    public function setWebsiteTextModified($websiteTextModified)
    {
        $this->websiteTextModified = $websiteTextModified;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getWebsiteTextModified()
    {
        return $this->websiteTextModified;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setWebsiteTextModifiedLanguage($text, $language)
    {
        switch (strtolower($language)) {
            
            case 'de':
            
                $this->websiteTextModifiedGerman = $text;
                break;
                
            case 'en':
        
                $this->websiteTextModifiedEnglish = $text;
                break;
                
            case 'nl':
            default:
        
                $this->websiteTextModifiedDutch = $text;
                break;
        }
        
        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * {@InheritDoc}
     */
    public function setWebsiteName($websiteName)
    {
        $this->websiteName = $websiteName;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getWebsiteName()
    {
        return $this->websiteName;
    }

    /**
     * {@InheritDoc}
     */
    public function setReviewed($reviewed)
    {
        $this->reviewed = $reviewed;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getReviewed()
    {
        return $this->reviewed;
    }

    /**
     * {@InheritDoc}
     */
    public function setTypeId($typeId)
    {
        $this->typeId = $typeId;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getTypeId()
    {
        return $this->typeId;
    }

    /**
     * {@InheritDoc}
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@InheritDoc}
     */
    public function setArrivalAt($arrivalAt)
    {
        $this->arrivalAt = $arrivalAt;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getArrivalAt()
    {
        return $this->arrivalAt;
    }

    /**
     * {@InheritDoc}
     */
    public function setDepartureAt($departureAt)
    {
        $this->departureAt = $departureAt;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getDepartureAt()
    {
        return $this->departureAt;
    }

    /**
     * {@InheritDoc}
     */
    public function setFilledInAt($filledInAt)
    {
        $this->filledInAt = $filledInAt;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getFilledInAt()
    {
        return $this->filledInAt;
    }

    /**
     * {@InheritDoc}
     */
    public function getOverallRating()
    {
        return $this->question_1_7;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setAnswers($answers)
    {
        foreach ($answers as $questionNumber => $data) {
            
            if (is_array($data)) {
             
                foreach ($data as $id => $answer) {
                
                    $property = 'question_' . $questionNumber . ($id === 0 ? '' : ('_' . $id));
                    $this->{$property} = $answer;
                }
                
            } else {
                
                $property = 'question_' . $questionNumber;
                $this->{$property} = $data;
            }
        }
        
        return $this;
    }
}
