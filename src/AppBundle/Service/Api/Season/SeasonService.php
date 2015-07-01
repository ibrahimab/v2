<?php
namespace AppBundle\Service\Api\Season;

class SeasonService
{
    /**
     * @var SeasonServiceRepositoryInterface
     */
    private $seasonServiceRepository;

    /**
     * Constructor
     *
     * @param SeasonServiceRepositoryInterface $seasonServiceRepository
     */
    public function __construct(SeasonServiceRepositoryInterface $seasonServiceRepository)
    {
        $this->seasonServiceRepository = $seasonServiceRepository;
    }

    /**
     * @return float
     */
    public function getInsurancesPolicyCosts()
    {
        return $this->seasonServiceRepository->getInsurancesPolicyCosts();
    }
}