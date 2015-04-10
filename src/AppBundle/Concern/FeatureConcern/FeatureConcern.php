<?php
namespace AppBundle\Concern\FeatureConcern;

use       AppBundle\Concern\FeatureConcern\Exception\FeatureNotFoundException;
use       AppBundle\Concern\FeatureConcern\Exception\FeaturesNotFoundException;

class FeatureConcern
{
    const FEATURE_SEASON_WINTER = 1;
    const FEATURE_SEASON_SUMMER = 2;
    
    /**
     * @var array
     */
    private $data;
    
    /**
     * @param array $data
     */
    public function __construct(Array $data)
    {
        $this->data = $data;
    }
    
    /**
     * @param integer $identifier
     * @return boolean
     */
    public function has($feature)
    {
        return in_array($feature, $this->data);
    }
    
    /**
     * @param integer $season
     * @param integer $feature
     * @throws SeasonNotFoundException
     * @throws FeatureNotFoundException
     */
    public function identifier($season, $feature)
    { 
        if (false === in_array($season, [self::FEATURE_SEASON_WINTER, self::FEATURE_SEASON_SUMMER])) {
            throw new SeasonNotFoundException(vsprintf('Season (%d) was not found', [$season]));
        }
        
        if (false === array_key_exists($feature, $this->identifiers[$season])) {
            throw new FeatureNotFoundException(vsprintf('Feature (%d) was not found', [$feature]));
        }
        
        return $this->identifiers[$season][$feature];
    }
    
    /**
     * @return array
     * @throws SeasonNotFoundException
     */
    public function get($season)
    {
        if (false === in_array($season, [self::FEATURE_SEASON_WINTER, self::FEATURE_SEASON_SUMMER])) {
            throw new SeasonNotFoundException(vsprintf('Season (%d) was not found', [$season]));
        }
        
        return array_values(array_intersect_key($this->identifiers[$season], array_flip($this->data)));
    }
    
    /**
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }
}