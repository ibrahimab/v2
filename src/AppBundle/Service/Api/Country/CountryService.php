<?php
namespace AppBundle\Service\Api\Country;

/**
 * This is the CountryService, with this service you can manipulate countries
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
class CountryService
{
    /**
     * @var CountryServiceRepositoryInterface
     */
    private $countryServiceRepository;
    
    /**
     * Constructor
     *
     * @param CountryServiceRepositoryInterface $countryServiceRepository
     */
    public function __construct(CountryServiceRepositoryInterface $countryServiceRepository)
    {
        $this->countryServiceRepository = $countryServiceRepository;
    }
    
    /**
     * Fetch active countries
     *
     * @param array $options
     * @return CountryServiceEntityInterface[]
     */
    public function findActive()
    {
        return $this->countryServiceRepository->findActive();
    }
    
    /**
     * Fetch all the countries
     *
     * Fetching all the countries based on the options passed in. The supported options are: 'where', 'order', 'limit', 'offset'
     *
     * @param array $options
     * @return CountryServiceEntityInterface
     */
    public function all($options = [])
    {
        return $this->countryServiceRepository->all($options);
    }
    
    /**
     * Finding a single country, based on criteria passed in
     *
     * @param array $by
     * @return CountryServiceEntityInterface
     */
    public function find($by = [])
    {
        return $this->countryServiceRepository->find($by);
    }
    
    /**
     * Finding a single country by name, locale included
     *
     * @param string $name
     * @param string $locale
     * @param string $sort
     * @return CountryServiceEntityInterface
     */
    public function findByLocaleName($name, $locale, $sort = 'alpha')
    {
        return $this->countryServiceRepository->findByLocaleName($name, $locale, $sort);
    }
    
    /**
     * Finding regions by country
     */
    public function findRegions(CountryServiceEntityInterface $country)
    {
        return $this->countryServiceRepository->findRegions($country);
    }
}