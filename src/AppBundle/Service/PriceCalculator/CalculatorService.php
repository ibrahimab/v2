<?php
namespace AppBundle\Service\PriceCalculator;

use       AppBundle\Service\PriceCalculator\FormService;
use       AppBundle\Concern\LocaleConcern;
use       AppBundle\Service\Api\Price\PriceService;
use       AppBundle\Service\Api\Option\OptionService;
use       AppBundle\Service\Api\Season\SeasonService;
use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;
use       Symfony\Component\Translation\TranslatorInterface;
use       IntlDateFormatter;

/**
 * PriceCalculatorService
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package chalet
 */
class CalculatorService
{
    /**
     * @var FormService
     */
    private $formService;

    /**
     * @var PriceService
     */
    private $priceService;

    /**
     * @var OptionService
     */
    private $optionService;

    /**
     * @var SeasonService
     */
    private $seasonService;

    /**
     * @var array
     */
    private $season;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var array
     */
    private $appConfig;

    /**
     * @var TypeServiceEntityInterface
     */
    private $type;

    /**
     * @var LocaleConcern
     */
    private $locale;

    /**
     * @var integer
     */
    private $person;

    /**
     * @var integer
     */
    private $weekend;

    /**
     * @var array
     */
    private $options;

    /**
     * @var array
     */
    private $insurances;

    /**
     * @var array
     */
    private $percentages;

    /**
     * @var float
     */
    private $policyCosts;


    /**
     * Constructor
     *
     * @param FormService $formService
     */
    public function __construct(FormService $formService)
    {
        $this->formService = $formService;
    }

    /**
     * @return FormService
     */
    public function getFormService()
    {
        if (null === $this->formService->getCalculatorService()) {
            $this->formService->setCalculatorService($this);
        }

        return $this->formService;
    }

    /**
     * @param  integer           $typeId
     * @return CalculatorService
     */
    public function setType(TypeServiceEntityInterface $type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param  LocaleConcern     $locale
     * @return CalculatorService
     */
    public function setLocale(LocaleConcern $locale)
    {
        $this->locale = $locale;
    }

    /**
     * @param  array $appConfig
     */
    public function setAppConfig($appConfig)
    {
        $this->appConfig = $appConfig;
    }

    /**
     * @return array
     */
    public function getAppConfig()
    {
        return $this->appConfig;
    }

    /**
     * @param  TranslatorInterface $translator
     * @return CalculatorService
     */
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param  OptionService $optionService
     * @return void
     */
    public function setOptionService(OptionService $optionService)
    {
        $this->optionService = $optionService;
    }

    /**
     * @param  PriceService
     * @return void
     */
    public function setPriceService(PriceService $priceService)
    {
        $this->priceService = $priceService;
    }

    /**
     * @param  SeasonService $seasonService
     * @return void
     */
    public function setSeasonService(SeasonService $seasonService)
    {
        $this->seasonService = $seasonService;
    }

    /**
     * @return array|void
     */
    public function getSeason()
    {
        if (null === $this->season) {

            $this->season = $this->seasonService->seasons();
            $this->season = (isset($this->season[0]) ? $this->season[0] : false);
        }

        return $this->season;
    }

    /**
     * @return TypeServiceEntityInterface
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param integer            $person
     * @return CalculatorService
     */
    public function setPerson($person)
    {
        $this->person = $person;
        return $this;
    }

    /**
     * @return integer
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * @return array
     */
    public function getPersons()
    {
        if (null === $this->persons) {

            $this->persons = [];
            $weekends      = $this->getWeekends();
            $persons       = $this->priceService->getBookablePersons($this->type->getId(), array_keys($weekends));
            $personLabel   = $this->translator->trans('person');
            $personsLabel  = $this->translator->trans('persons');

            foreach ($persons as $person) {
                $this->persons[] = sprintf('%d %s', $person, strtolower($person > 1 ? $personsLabel : $personLabel));
            }
        }

        return $this->persons;
    }

    /**
	 * @param integer 			  $weekend
	 * @return CalculatorService
	 */
	public function setWeekend($weekend)
	{
        $this->weekend = $weekend;
        return $this;
    }

    /**
     * @return integer
     */
    public function getWeekend()
	{
        return $this->weekend;
    }

    /**
     * @return integer
     */
    public function getWeekends()
    {
        if (null === $this->weekends) {

            $this->weekends = [];
            $weekends       = $this->priceService->getAvailableData($this->type)['weekends'];
            $formatter      = new IntlDateFormatter($locale, IntlDateFormatter::FULL, IntlDateFormatter::FULL, $timezone, IntlDateFormatter::GREGORIAN);
            $date           = new \DateTime();

            $formatter->setPattern('eeee dd MMMM y');

            foreach ($weekends as $weekend) {
                $this->weekends[$weekend] = $formatter->format($date->setTimestamp($weekend));
            }
        }

        return $this->weekends;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        if (null === $this->options) {

            $season        = (false !== $this->getSeason() ? $this->getSeason()['id'] : null);
            $this->options = $this->optionService->calculatorOptions($this->type->getAccommodationId(), $season, $this->getWeekend());
        }

        return $this->options;
    }

    /**
     * @return array
     */
    public function getAvailableData()
    {
        if (null === $this->availableData) {
            $this->availableData = $this->priceService->getAvailableData($this->type);
        }

        return $this->availableData;
    }

    /**
     * @return array
     */
    public function getCancellationInsurances()
    {
        if (null === $this->insurances) {

            $this->insurances = array_filter($this->getAppConfig()['cancellation_insurances'], function($insurance) {
                return (true === $insurance['active']);
            });
        }

        return $this->insurances;
    }

    /**
     * @return array
     */
    public function getCancellationPercentages()
    {
        if (null === $this->percentages) {
            $this->percentages = $this->getAvailableData()['cancellation_insurances'];
        }

        return $this->percentages;
    }

    /**
     * @return float
     */
    public function getPolicyCosts()
    {
        if (null === $this->policyCosts) {
            $this->policyCosts = $this->getAvailableData()['insurance_policy_costs'];
        }

        return $this->policyCosts;
    }
}