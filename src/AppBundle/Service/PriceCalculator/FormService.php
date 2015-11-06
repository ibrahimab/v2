<?php
namespace AppBundle\Service\PriceCalculator;

use       AppBundle\Form\PriceCalculator\StepOneForm;
use       AppBundle\Form\PriceCalculator\StepTwoForm;
use       AppBundle\Entity\Form\PriceCalculator\StepOne as StepOneEntity;
use       AppBundle\Entity\Form\PriceCalculator\StepTwo as StepTwoEntity;
use       Symfony\Component\Form\FormFactoryInterface;
use		  Symfony\Component\Form\FormTypeInterface;
use		  Symfony\Component\Form\FormView;
use       Symfony\Component\Form\Form;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.2.7
 * @since   0.2.7
 */
class FormService
{
    /**
     * @const integer
     */
    const FORM_STEP_ONE = 1;

    /**
     * @const integer
     */
    const FORM_STEP_TWO = 2;

    /**
     * @var FormFactoryInterface
     */
    private $factory;

    /**
     * @var string
     */
    private $locale;

    /**
     * @var array
     */
    private $forms = [];

    /**
     * @var array
     */
    private $entities = [];

    /**
     * @var CalculatorService
     */
    private $calculatorService;


    /**
     * Constructor
     *
     * @param StepOneForm          $stepOne
     * @param FormFactoryInterface $factory
     */
    public function __construct(FormFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param  string      $locale
     * @return FormService
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @param  CalculatorService $CalculatorService
     * @return FormService
     */
    public function setCalculatorService(CalculatorService $calculatorService)
    {
        $this->calculatorService = $calculatorService;
        return $this;
    }

    /**
     * @return CalculatorServices
     */
    public function getCalculatorService()
    {
        return $this->calculatorService;
    }

    /**
     * @param  StepOneForm $form
     * @return FormService
     */
    public function setStepOneForm(StepOneForm $form)
    {
        $this->forms[self::FORM_STEP_ONE] = $form;
        return $this;
    }

    /**
     * @param  StepTwoForm $form
     * @return FormService
     */
    public function setStepTwoForm(StepTwoForm $form)
    {
        $this->forms[self::FORM_STEP_TWO] = $form;
        return $this;
    }

    /**
     * @param  integer     $formId
     * @return StepOneForm
     */
    public function getForm($formId)
    {
        if (!isset($this->forms[$formId])) {
            return null;
        }

        return $this->forms[$formId];
    }

	/**
	 * @param  integer  $form
	 * @return FormView
	 */
	public function create($formId, $options = [])
	{
        if (null === ($form = $this->getForm($formId))) {
            throw new FormNotFoundException(sprintf('Could not find form with ID=%s', $formId));
        }

		return $this->factory->create($form, $this->getEntity($formId));
	}

    /**
     * @param  integer       $formId
     * @return stepOneEntity
     */
    public function getEntity($formId)
    {
        switch ($formId) {

            case self::FORM_STEP_ONE:
                $entity = $this->getStepOneEntity();
            break;

            case self::FORM_STEP_TWO:
                $entity = $this->getStepTwoEntity();
            break;

            default:
                throw new EntityNotFoundException(sprintf('Could not found entity for form ID=%s', $formId));
        }

        return $entity;
    }

    /**
     * @return StepOneEntity
     */
    public function getStepOneEntity()
    {
        if (!isset($this->entities[self::FORM_STEP_ONE])) {

            $entity           = new StepOneEntity();
            $entity->type     = $this->calculatorService->getType()->getLocaleName($this->locale->get());
            $entity->person   = $this->calculatorService->getPerson();
            $entity->persons  = $this->calculatorService->getPersons();
            $entity->weekend  = $this->calculatorService->getWeekend();
            $entity->weekends = $this->calculatorService->getWeekends();

            $this->entities[self::FORM_STEP_ONE] = $entity;
        }

        return $this->entities[self::FORM_STEP_ONE];
    }

    /**
     * @return StepTwoEntity
     */
    public function getStepTwoEntity()
    {
        if (!isset($this->entities[self::FORM_STEP_TWO])) {

            $this->entities[self::FORM_STEP_TWO] = new StepTwoEntity($this->calculatorService->getOptions(),
                                                                     $this->calculatorService->getPerson(),
                                                                     $this->calculatorService->getCancellationInsurances(),
                                                                     $this->calculatorService->getCancellationPercentages(),
                                                                     $this->calculatorService->getPolicyCosts());
        }

        return $this->entities[self::FORM_STEP_TWO];
    }
}