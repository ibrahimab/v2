<?php
namespace AppBundle\Service\Api\Option;
use       Symfony\Component\DependencyInjection\ContainerInterface;

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
    public function __construct(ContainerInterface $container)
    {
        $this->container               = $container;
        $this->optionServiceRepository = $this->container->get('app.repository.option');
    }

    /**
     * @return string
     */
    public function getTravelInsurancesDescription()
    {
        return $this->optionServiceRepository->getTravelInsurancesDescription();
    }
    
    public function options($type)
    {
        $wrapper = $this->container->get('old.rate.table.wrapper');
        $wrapper->setType($type);
        
        $seasons       = $wrapper->getSeasonRates();
        $accommodation = $this->accommodation($type->getAccommodation());
    }
    
    public function accommodation($accommodation)
    {
        return $this->optionServiceRepository->accommodation($accommodation);
    }
}