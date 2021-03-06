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

    /**
     * @param integer $accommodationId
     * @param integer $weekend
     * @return array
     */
    public function options($accommodationId, $season = null, $weekend = null)
    {
        return $this->optionServiceRepository->options($accommodationId, $season, $weekend);
    }

    /**
     * @param integer $optionId
     * @return string
     */
    public function option($optionId)
    {
        return $this->optionServiceRepository->option($optionId);
    }

    /**
     * @param  integer $accommodationId
     * @param  integer $season
     * @param  integer $weekend
     *
     * @return integer
     */
    public function calculatorOptions($accommodationId, $season, $weekend)
    {
        return $this->optionServiceRepository->calculatorOptions($accommodationId, $season, $weekend);
    }

    /**
     * @param integer $typeId
     *
     * @return array
     */
    public function datesOptions($typeId)
    {
        return $this->optionServiceRepository->datesOptions($typeId);
    }
}