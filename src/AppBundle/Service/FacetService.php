<?php
namespace AppBundle\Service;
use       AppBundle\Service\Api\Search\FilterBuilder;
use       AppBundle\Service\Paginator\PaginatorService;
use       AppBundle\Service\Api\Accommodation\AccommodationServiceEntityInterface;
use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;

/**
 * FacetService
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class FacetService
{
    /**
     * @var array
     */
    private $filters;
    
    /**
     * @var PaginatorService
     */
    private $paginator;
    
    /**
     * @var array
     */
    private $facets;
    
    /**
     * Constructor
     *
     * @param PaginatorService $paginator
     * @param array $filters
     */
    public function __construct($paginator, $filters)
    {
        $this->paginator = $paginator;
        $this->filters   = $filters;
        $this->facets    = [];
    }
    
    /**
     * @param integer $filter
     * @param integer $value
     * @return boolean
     */
    public function has($filter, $value)
    {
        return isset($this->filters[$filter]) && in_array($value, $this->filters[$filter]);
    }
    
    /**
     * @param integer $filter
     * @param integer $value
     * @return integer
     */
    public function get($filter, $value)
    {
        if (!isset($this->facets[$filter])) {
            return 0;
        }
        
        if (!isset($this->facets[$filter][$value])) {
            return 0;
        }
        
        return $this->facets[$filter][$value];
    }
    
    /**
     * @param integer|null $filter
     * @return array
     */
    public function facets($filter = null)
    {
        return (null !== $filter && isset($this->facets[$filter]) ? $this->facets[$filter] : $this->facets);
    }
    
    /**
     * Calculate the facets
     */
    public function calculate()
    {
        $results = $this->paginator->results();
        
        foreach ($results as $accommodation) {
            $this->accommodation($accommodation);
        }
    }
    
    /**
     * @param AccommodationServiceEntityInterface $accommodation
     */
    public function accommodation(AccommodationServiceEntityInterface $accommodation)
    {
        $types = $accommodation->getTypes();
        
        foreach ($types as $type) {
            $this->filters($accommodation, $type);
        }
    }
    
    /**
     * @param AccommodationServiceEntityInterface $accommodation
     * @param TypeServiceEntityInterface $type
     */
    public function filters(AccommodationServiceEntityInterface $accommodation, TypeServiceEntityInterface $type)
    {
        foreach ($this->filters as $filter => $values) {
            $this->filter($accommodation, $type, $filter, $values);
        }
    }
    
    /**
     * @param AccommodationServiceEntityInterface $accommodation
     * @param TypeServiceEntityInterface $type
     * @param integer $filter
     * @param integer $value
     */
    public function filter(AccommodationServiceEntityInterface $accommodation, TypeServiceEntityInterface $type, $filter, $values)
    {
        foreach ($values as $value) {
            
            switch ($filter) {
        
                case FilterService::FILTER_DISTANCE:
                    $this->distance($accommodation, $filter, $value);
                break;
        
                case FilterService::FILTER_LENGTH:
                    $this->length($accommodation, $filter, $value);
                break;
        
                case FilterService::FILTER_FACILITY:
                    $this->facility($accommodation, $type, $filter, $value);
                break;
        
                case FilterService::FILTER_THEME:
                    $this->theme($accommodation, $type, $filter, $value);
                break;
            }
        }
    }
    
    /**
     * @param integer $filter
     * @param integer $value
     */
    public function increment($filter, $value)
    {
        if (!isset($this->facets[$filter])) {
            $this->facets[$filter] = [];
        }
        
        if (!isset($this->facets[$filter][$value])) {
            $this->facets[$filter][$value] = 0;
        }
        
        $this->facets[$filter][$value] += 1;
    }
    
    /**
     * @param AccommodationServiceEntityInterface $accommodation
     * @param integer $filter
     * @param integer $value
     */
    public function distance(AccommodationServiceEntityInterface $accommodation, $filter, $value)
    {
        $distanceSlope = $accommodation->getDistanceSlope();
        switch ($value) {
    
            case FilterService::FILTER_DISTANCE_BY_SLOPE:
        
                if ($distanceSlope <= FilterBuilder::VALUE_DISTANCE_BY_SLOPE) {
                    $this->increment($filter, $value);
                }
        
            break;
    
            case FilterService::FILTER_DISTANCE_MAX_250:
        
                if ($distanceSlope <= 250) {
                    $this->increment($filter, $value);
                }
            
            break;
    
            case FilterService::FILTER_DISTANCE_MAX_500:
        
                if ($distanceSlope <= 500) {
                    $this->increment($filter, $value);
                }
        
            break;
    
            case FilterService::FILTER_DISTANCE_MAX_1000:
        
                if ($distanceSlope <= 1000) {
                    $this->increment($filter, $value);
                }
            
            break;
        }
    }
    
    /**
     * @param AccommodationServiceEntityInterface $accommodation
     * @param integer $filter
     * @param integer $value
     */
    public function length(AccommodationServiceEntityInterface $accommodation, $filter, $value)
    {
        $length = $accommodation->getPlace()->getRegion()->getTotalSlopesDistance();
        switch ($value) {
        
            case FilterService::FILTER_LENGTH_MAX_100:
        
                if ($length < 100) {
                    $this->increment($filter, $value);
                }
        
            break;
        
            case FilterService::FILTER_LENGTH_MIN_100:
        
                if ($length >= 100) {
                    $this->increment($filter, $value);
                }
        
            break;
        
            case FilterService::FILTER_LENGTH_MIN_200:
        
                if ($length >= 200) {
                    $this->increment($filter, $value);
                }
        
            break;
        
            case FilterService::FILTER_LENGTH_MIN_400:
        
                if ($length >= 400) {
                    $this->increment($filter, $value);
                }
        
            break;
        }
    }
    
    /**
     * @param AccommodationServiceEntityInterface $accommodation
     * @param TypeServiceEntityInterface $type
     * @param integer $filter
     * @param integer $value
     */
    public function facility(AccommodationServiceEntityInterface $accommodation, TypeServiceEntityInterface $type, $filter, $value)
    {
        switch ($value) {
    
            case FilterService::FILTER_FACILITY_CATERING:
            
                if (in_array(1, $accommodation->getFeatures()) || in_array(1, $type->getFeatures())) {
                    $this->increment($filter, $value);
                }
                
            break;
    
            case FilterService::FILTER_FACILITY_INTERNET_WIFI:
            
                if (in_array(21, $accommodation->getFeatures()) || in_array(23, $accommodation->getFeatures()) || in_array(20, $type->getFeatures()) || in_array(22, $type->getFeatures())) {
                    $this->increment($filter, $value);
                }
            
            break;
    
            case FilterService::FILTER_FACILITY_SWIMMING_POOL:
            
                if (in_array(4, $accommodation->getFeatures()) || in_array(11, $accommodation->getFeatures()) || in_array(4, $type->getFeatures())) {
                    $this->increment($filter, $value);
                }
            
            break;
    
            case FilterService::FILTER_FACILITY_SAUNA:
            
                if (in_array(3, $accommodation->getFeatures()) || in_array(10, $accommodation->getFeatures()) || in_array(3, $type->getFeatures())) {
                    $this->increment($filter, $value);
                }
            
            break;
    
            case FilterService::FILTER_FACILITY_PRIVATE_SAUNA:
            
                if (in_array(3, $accommodation->getFeatures()) || in_array(3, $type->getFeatures())) {
                    $this->increment($filter, $value);
                }
            
            break;
    
            case FilterService::FILTER_FACILITY_PETS_ALLOWED:
            
                if (in_array(13, $accommodation->getFeatures()) || in_array(11, $type->getFeatures())) {
                    $this->increment($filter, $value);
                }
            
            break;
    
            case FilterService::FILTER_FACILITY_FIREPLACE:
            
                if (in_array(12, $accommodation->getFeatures()) || in_array(10, $type->getFeatures())) {
                    $this->increment($filter, $value);
                }
            
            break;
        }
    }
    
    /**
     * @param AccommodationServiceEntityInterface $accommodation
     * @param TypeServiceEntityInterface $type
     * @param integer $filter
     * @param integer $value
     */
    public function theme(AccommodationServiceEntityInterface $accommodation, TypeServiceEntityInterface $type, $filter, $value)
    {
        switch ($value) {
            
            case FilterService::FILTER_THEME_KIDS:
            
                if (in_array(5, $accommodation->getFeatures()) || in_array(5, $type->getFeatures())) {
                    $this->increment($filter, $value);
                }
            
            break;
            
            case FilterService::FILTER_THEME_CHARMING_PLACES:
            
                if (in_array(13, $accommodation->getPlace()->getFeatures())) {
                    $this->increment($filter, $value);
                }
            
            break;
            
            case FilterService::FILTER_THEME_WINTER_WELLNESS:
            
                if (in_array(9, $accommodation->getFeatures()) || in_array(9, $type->getFeatures())) {
                    $this->increment($filter, $value);
                }
                    
            break;
            
            case FilterService::FILTER_THEME_SUPER_SKI_STATIONS:
            
                if (in_array(14, $accommodation->getPlace()->getFeatures())) {
                    $this->increment($filter, $value);
                }
            
            break;
            
            case FilterService::FILTER_THEME_10_FOR_APRES_SKI:
            
                if (in_array(6, $accommodation->getPlace()->getFeatures())) {
                    $this->increment($filter, $value);
                }
            
            break;
        }
    }
}