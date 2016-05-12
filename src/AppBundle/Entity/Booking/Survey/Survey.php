<?php
namespace AppBundle\Entity\Booking\Survey;

use AppBundle\Concern\WebsiteConcern;
use AppBundle\Service\Api\Booking\BookingServiceEntityInterface;
use AppBundle\Service\Api\Booking\Survey\SurveyServiceEntityInterface;
use Doctrine\ORM\Mapping as ORM;
use Jenssegers\Date\Date;

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
     * Average
     *
     * @var float
     */
    private $average = 0.0;

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
    private $originalWebsiteTextModified;

    /**
     * @var string
     *
     * @ORM\Column(name="websitetekst_gewijzigd_nl", type="text")
     */
    private $dutchWebsiteTextModified;

    /**
     * @var string
     *
     * @ORM\Column(name="websitetekst_gewijzigd_en", type="text")
     */
    private $englishWebsiteTextModified;

    /**
     * @var string
     *
     * @ORM\Column(name="websitetekst_gewijzigd_de", type="text")
     */
    private $germanWebsiteTextModified;

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
    private $ratingAccommodationReception;

    /**
     * @var integer
     *
     * @ORM\Column(name="vraag1_2", type="integer")
     */
    private $ratingAccommodationLocation;

    /**
     * @var integer
     *
     * @ORM\Column(name="vraag1_3", type="integer")
     */
    private $ratingAccommodationComfort;

    /**
     * @var integer
     *
     * @ORM\Column(name="vraag1_4", type="integer")
     */
    private $ratingAccommodationCleaning;

    /**
     * @var integer
     *
     * @ORM\Column(name="vraag1_5", type="integer")
     */
    private $ratingAccommodationFacilities;

    /**
     * @var integer
     *
     * @ORM\Column(name="vraag1_6", type="integer")
     */
    private $ratingAccommodationPriceQuality;

    /**
     * @var integer
     *
     * @ORM\Column(name="vraag1_7", type="integer")
     */
    private $ratingAccommodationTotal;

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
    public function setAverage($average)
    {
        $this->average = $average;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getAverage()
    {
        return $this->average;
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
    public function setOriginalWebsiteTextModified($originalWebsiteTextModified)
    {
        $this->originalWebsiteTextModified = $originalWebsiteTextModified;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getOriginalWebsiteTextModified()
    {
        return $this->originalWebsiteTextModified;
    }

    /**
     * {@InheritDoc}
     */
    public function setDutchWebsiteTextModified($dutchWebsiteTextModified)
    {
        $this->dutchWebsiteTextModified = $dutchWebsiteTextModified;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getDutchWebsiteTextModified()
    {
        return $this->dutchWebsiteTextModified;
    }

    /**
     * {@InheritDoc}
     */
    public function setEnglishWebsiteTextModified($englishWebsiteTextModified)
    {
        $this->englishWebsiteTextModified = $englishWebsiteTextModified;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getEnglishWebsiteTextModified()
    {
        return $this->englishWebsiteTextModified;
    }

    /**
     * {@InheritDoc}
     */
    public function setGermanWebsiteTextModified($germanWebsiteTextModified)
    {
        $this->germanWebsiteTextModified = $germanWebsiteTextModified;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getGermanWebsiteTextModified()
    {
        return $this->germanWebsiteTextModified;
    }

    /**
     * {@InheritDoc}
     */
    public function setLocaleWebsiteTextModified($text, $locale)
    {
        switch (strtolower($locale)) {

            case 'de':

                $this->setGermanWebsiteTextModified($text);
                break;

            case 'en':

                $this->setEnglishWebsiteTextModified($text);
                break;

            case 'nl':
            default:

                $this->setDutchWebsiteTextModified($text);
                break;
        }

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getLocaleWebsiteTextModified($locale)
    {
        $locale = strtolower($locale);

        if ($this->getLanguage() === $locale) {
            return $this->getOriginalWebsiteTextModified();
        }

        return $this->getLocaleField('websiteTextModified', $locale, ['nl', 'en', 'de']);
    }

    /**
     * {@InheritDoc}
     */
    public function getFlag($website)
    {
        switch (true) {

            case in_array($website, [WebsiteConcern::CHALET_BE, WebsiteConcern::ITALISSIMA_BE]):

                $flag = 'be';
                break;

            case in_array($website, [WebsiteConcern::CHALET_EU, WebsiteConcern::WEBSITE_CHALETS_IN_VALLANDRY_COM, WebsiteConcern::ITALYHOMES_EU]):

                $flag = 'en';
                break;

            case in_array($website, [WebsiteConcern::CHALET_ONLINE_DE]):

                $flag = 'de';
                break;

            case in_array($website, [WebsiteConcern::WEBSITE_CHALET_NL,
                                     WebsiteConcern::WEBSITE_CHALET_TOUR_NL,
                                     WebsiteConcern::WEBSITE_CHALETS_IN_VALLANDRY,
                                     WebsiteConcern::WEBSITE_VENTURASOL_NL,
                                     WebsiteConcern::WEBSITE_VENTURASOL_VACANCES_NL,
                                     WebsiteConcern::ZOMERHUISJE_NL,
                                     WebsiteConcern::ITALISSIMA_NL]):
            default:

                $flag = 'nl';
                break;
        }
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
        return $this->ratingAccommodationTotal;
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

    /**
     * {@InheritDoc}
     */
    public function getAnswer($questionNumber, $n)
    {
        $property = 'question_' . $questionNumber . '_' . $n;

        if (false === property_exists($this, $property)) {
            return false;
        }

        return ($this->{$property} > 10 ? 10 : $this->{$property});
    }

    /**
     * {@InheritDoc}
     */
    public function getBookingDate()
    {
        $booking = $this->getBooking();

        if (null !== $booking) {

            $date = new Date($booking->getExactArrivalAt()->getTimestamp());
            return $date->format('F Y');
        }

        return '';
    }

    /**
     * {@InheritDoc}
     */
    public function getLocaleField($field, $locale, $allowedLocales)
    {
        $locale        = strtolower($locale);
        $allowedLocale = in_array($locale, $allowedLocales);

        switch (true) {

            case $allowedLocale && $locale === 'en':
                $localized = $this->{'getEnglish' . $field}();
                break;

            case $allowedLocale && $locale === 'de':
                $localized = $this->{'getGerman' . $field}();
                break;

            case $allowedLocale && $locale === 'nl':
            default:
                $localized = $this->{'getDutch' . $field}();
                break;
        }

        return $localized;
    }

    /**
     * Set ratingAccommodationReception
     *
     * @param integer $ratingAccommodationReception
     * @return Survey
     */
    public function setRatingAccommodationReception($ratingAccommodationReception)
    {
        $this->ratingAccommodationReception = $ratingAccommodationReception;

        return $this;
    }

    /**
     * Get ratingAccommodationReception
     *
     * @return integer
     */
    public function getRatingAccommodationReception()
    {
        return $this->ratingAccommodationReception;
    }

    /**
     * Set ratingAccommodationLocation
     *
     * @param integer $ratingAccommodationLocation
     * @return Survey
     */
    public function setRatingAccommodationLocation($ratingAccommodationLocation)
    {
        $this->ratingAccommodationLocation = $ratingAccommodationLocation;

        return $this;
    }

    /**
     * Get ratingAccommodationLocation
     *
     * @return integer
     */
    public function getRatingAccommodationLocation()
    {
        return $this->ratingAccommodationLocation;
    }

    /**
     * Set ratingAccommodationComfort
     *
     * @param integer $ratingAccommodationComfort
     * @return Survey
     */
    public function setRatingAccommodationComfort($ratingAccommodationComfort)
    {
        $this->ratingAccommodationComfort = $ratingAccommodationComfort;

        return $this;
    }

    /**
     * Get ratingAccommodationComfort
     *
     * @return integer
     */
    public function getRatingAccommodationComfort()
    {
        return $this->ratingAccommodationComfort;
    }

    /**
     * Set ratingAccommodationCleaning
     *
     * @param integer $ratingAccommodationCleaning
     * @return Survey
     */
    public function setRatingAccommodationCleaning($ratingAccommodationCleaning)
    {
        $this->ratingAccommodationCleaning = $ratingAccommodationCleaning;

        return $this;
    }

    /**
     * Get ratingAccommodationCleaning
     *
     * @return integer
     */
    public function getRatingAccommodationCleaning()
    {
        return $this->ratingAccommodationCleaning;
    }

    /**
     * Set ratingAccommodationFacilities
     *
     * @param integer $ratingAccommodationFacilities
     * @return Survey
     */
    public function setRatingAccommodationFacilities($ratingAccommodationFacilities)
    {
        $this->ratingAccommodationFacilities = $ratingAccommodationFacilities;

        return $this;
    }

    /**
     * Get ratingAccommodationFacilities
     *
     * @return integer
     */
    public function getRatingAccommodationFacilities()
    {
        return $this->ratingAccommodationFacilities;
    }

    /**
     * Set ratingAccommodationPriceQuality
     *
     * @param integer $ratingAccommodationPriceQuality
     * @return Survey
     */
    public function setRatingAccommodationPriceQuality($ratingAccommodationPriceQuality)
    {
        $this->ratingAccommodationPriceQuality = $ratingAccommodationPriceQuality;

        return $this;
    }

    /**
     * Get ratingAccommodationPriceQuality
     *
     * @return integer
     */
    public function getRatingAccommodationPriceQuality()
    {
        return $this->ratingAccommodationPriceQuality;
    }

    /**
     * Set ratingAccommodationTotal
     *
     * @param integer $ratingAccommodationTotal
     * @return Survey
     */
    public function setRatingAccommodationTotal($ratingAccommodationTotal)
    {
        $this->ratingAccommodationTotal = $ratingAccommodationTotal;

        return $this;
    }

    /**
     * Get ratingAccommodationTotal
     *
     * @return integer
     */
    public function getRatingAccommodationTotal()
    {
        return $this->ratingAccommodationTotal;
    }

    /**
     * Set question_1_explanation
     *
     * @param integer $question1Explanation
     * @return Survey
     */
    public function setQuestion1Explanation($question1Explanation)
    {
        $this->question_1_explanation = $question1Explanation;

        return $this;
    }

    /**
     * Get question_1_explanation
     *
     * @return integer
     */
    public function getQuestion1Explanation()
    {
        return $this->question_1_explanation;
    }

    /**
     * Set question_2_1
     *
     * @param integer $question21
     * @return Survey
     */
    public function setQuestion21($question21)
    {
        $this->question_2_1 = $question21;

        return $this;
    }

    /**
     * Get question_2_1
     *
     * @return integer
     */
    public function getQuestion21()
    {
        return $this->question_2_1;
    }

    /**
     * Set question_2_2
     *
     * @param integer $question22
     * @return Survey
     */
    public function setQuestion22($question22)
    {
        $this->question_2_2 = $question22;

        return $this;
    }

    /**
     * Get question_2_2
     *
     * @return integer
     */
    public function getQuestion22()
    {
        return $this->question_2_2;
    }

    /**
     * Set question_2_3
     *
     * @param integer $question23
     * @return Survey
     */
    public function setQuestion23($question23)
    {
        $this->question_2_3 = $question23;

        return $this;
    }

    /**
     * Get question_2_3
     *
     * @return integer
     */
    public function getQuestion23()
    {
        return $this->question_2_3;
    }

    /**
     * Set question_2_4
     *
     * @param integer $question24
     * @return Survey
     */
    public function setQuestion24($question24)
    {
        $this->question_2_4 = $question24;

        return $this;
    }

    /**
     * Get question_2_4
     *
     * @return integer
     */
    public function getQuestion24()
    {
        return $this->question_2_4;
    }

    /**
     * Set question_2_5
     *
     * @param integer $question25
     * @return Survey
     */
    public function setQuestion25($question25)
    {
        $this->question_2_5 = $question25;

        return $this;
    }

    /**
     * Get question_2_5
     *
     * @return integer
     */
    public function getQuestion25()
    {
        return $this->question_2_5;
    }

    /**
     * Set question_2_6
     *
     * @param integer $question26
     * @return Survey
     */
    public function setQuestion26($question26)
    {
        $this->question_2_6 = $question26;

        return $this;
    }

    /**
     * Get question_2_6
     *
     * @return integer
     */
    public function getQuestion26()
    {
        return $this->question_2_6;
    }

    /**
     * Set question_2_7
     *
     * @param integer $question27
     * @return Survey
     */
    public function setQuestion27($question27)
    {
        $this->question_2_7 = $question27;

        return $this;
    }

    /**
     * Get question_2_7
     *
     * @return integer
     */
    public function getQuestion27()
    {
        return $this->question_2_7;
    }

    /**
     * Set question_2_other
     *
     * @param integer $question2Other
     * @return Survey
     */
    public function setQuestion2Other($question2Other)
    {
        $this->question_2_other = $question2Other;

        return $this;
    }

    /**
     * Get question_2_other
     *
     * @return integer
     */
    public function getQuestion2Other()
    {
        return $this->question_2_other;
    }

    /**
     * Set question_3_1
     *
     * @param integer $question31
     * @return Survey
     */
    public function setQuestion31($question31)
    {
        $this->question_3_1 = $question31;

        return $this;
    }

    /**
     * Get question_3_1
     *
     * @return integer
     */
    public function getQuestion31()
    {
        return $this->question_3_1;
    }

    /**
     * Set question_3_2
     *
     * @param integer $question32
     * @return Survey
     */
    public function setQuestion32($question32)
    {
        $this->question_3_2 = $question32;

        return $this;
    }

    /**
     * Get question_3_2
     *
     * @return integer
     */
    public function getQuestion32()
    {
        return $this->question_3_2;
    }

    /**
     * Set question_3_3
     *
     * @param integer $question33
     * @return Survey
     */
    public function setQuestion33($question33)
    {
        $this->question_3_3 = $question33;

        return $this;
    }

    /**
     * Get question_3_3
     *
     * @return integer
     */
    public function getQuestion33()
    {
        return $this->question_3_3;
    }

    /**
     * Set question_3_4
     *
     * @param integer $question34
     * @return Survey
     */
    public function setQuestion34($question34)
    {
        $this->question_3_4 = $question34;

        return $this;
    }

    /**
     * Get question_3_4
     *
     * @return integer
     */
    public function getQuestion34()
    {
        return $this->question_3_4;
    }

    /**
     * Set question_3_5
     *
     * @param integer $question35
     * @return Survey
     */
    public function setQuestion35($question35)
    {
        $this->question_3_5 = $question35;

        return $this;
    }

    /**
     * Get question_3_5
     *
     * @return integer
     */
    public function getQuestion35()
    {
        return $this->question_3_5;
    }

    /**
     * Set question_3_6
     *
     * @param integer $question36
     * @return Survey
     */
    public function setQuestion36($question36)
    {
        $this->question_3_6 = $question36;

        return $this;
    }

    /**
     * Get question_3_6
     *
     * @return integer
     */
    public function getQuestion36()
    {
        return $this->question_3_6;
    }

    /**
     * Set question_3_7
     *
     * @param integer $question37
     * @return Survey
     */
    public function setQuestion37($question37)
    {
        $this->question_3_7 = $question37;

        return $this;
    }

    /**
     * Get question_3_7
     *
     * @return integer
     */
    public function getQuestion37()
    {
        return $this->question_3_7;
    }

    /**
     * Set question_3_8
     *
     * @param integer $question38
     * @return Survey
     */
    public function setQuestion38($question38)
    {
        $this->question_3_8 = $question38;

        return $this;
    }

    /**
     * Get question_3_8
     *
     * @return integer
     */
    public function getQuestion38()
    {
        return $this->question_3_8;
    }

    /**
     * Set question_3_9
     *
     * @param integer $question39
     * @return Survey
     */
    public function setQuestion39($question39)
    {
        $this->question_3_9 = $question39;

        return $this;
    }

    /**
     * Get question_3_9
     *
     * @return integer
     */
    public function getQuestion39()
    {
        return $this->question_3_9;
    }

    /**
     * Set question_3_explanation
     *
     * @param integer $question3Explanation
     * @return Survey
     */
    public function setQuestion3Explanation($question3Explanation)
    {
        $this->question_3_explanation = $question3Explanation;

        return $this;
    }

    /**
     * Get question_3_explanation
     *
     * @return integer
     */
    public function getQuestion3Explanation()
    {
        return $this->question_3_explanation;
    }

    /**
     * Set question_4
     *
     * @param integer $question4
     * @return Survey
     */
    public function setQuestion4($question4)
    {
        $this->question_4 = $question4;

        return $this;
    }

    /**
     * Get question_4
     *
     * @return integer
     */
    public function getQuestion4()
    {
        return $this->question_4;
    }

    /**
     * Set question_5
     *
     * @param integer $question5
     * @return Survey
     */
    public function setQuestion5($question5)
    {
        $this->question_5 = $question5;

        return $this;
    }

    /**
     * Get question_5
     *
     * @return integer
     */
    public function getQuestion5()
    {
        return $this->question_5;
    }

    /**
     * Set question_5_explanation
     *
     * @param integer $question5Explanation
     * @return Survey
     */
    public function setQuestion5Explanation($question5Explanation)
    {
        $this->question_5_explanation = $question5Explanation;

        return $this;
    }

    /**
     * Get question_5_explanation
     *
     * @return integer
     */
    public function getQuestion5Explanation()
    {
        return $this->question_5_explanation;
    }

    /**
     * Set question_6
     *
     * @param integer $question6
     * @return Survey
     */
    public function setQuestion6($question6)
    {
        $this->question_6 = $question6;

        return $this;
    }

    /**
     * Get question_6
     *
     * @return integer
     */
    public function getQuestion6()
    {
        return $this->question_6;
    }

    /**
     * Set question_7
     *
     * @param integer $question7
     * @return Survey
     */
    public function setQuestion7($question7)
    {
        $this->question_7 = $question7;

        return $this;
    }

    /**
     * Get question_7
     *
     * @return integer
     */
    public function getQuestion7()
    {
        return $this->question_7;
    }
}
