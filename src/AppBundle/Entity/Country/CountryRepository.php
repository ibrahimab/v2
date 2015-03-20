<?php
namespace AppBundle\Entity\Country;

use       AppBundle\Entity\BaseRepository;
use       AppBundle\Service\Api\Country\CountryServiceRepositoryInterface;
use       Doctrine\ORM\EntityRepository;

/**
 * CountryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CountryRepository extends BaseRepository implements CountryServiceRepositoryInterface
{
    public function findByLocaleName($name, $locale)
    {
        $locale = strtolower($locale);
        switch ($locale) {
            
            case 'en':
                $field = 'englishName';
                break;
                
            case 'de':
                $field = 'germanName';
                break;
                
            case 'nl':
            default:
                $field = 'name';
                break;
        }
        
        return $this->find([$field => $name]);
    }
}
