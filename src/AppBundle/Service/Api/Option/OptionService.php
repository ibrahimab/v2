<?php
namespace AppBundle\Service\Api\Option;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class OptionService
{
    /**
     * @var OptionServiceRepositoryInterface
     */
    private $optionServiceRepository;

    /**
     * Constructor
     *
     * @param OptionServiceRepositoryInterface $optionServiceRepository
     */
    public function __construct(OptionServiceRepositoryInterface $optionServiceRepository)
    {
        $this->optionServiceRepository = $optionServiceRepository;
    }

    /**
     * @return string
     */
    public function getTravelInsurancesDescription()
    {
        return $this->optionServiceRepository->getTravelInsurancesDescription();
    }
}