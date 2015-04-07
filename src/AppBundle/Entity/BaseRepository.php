<?php
namespace AppBundle\Entity;
use       Doctrine\ORM\EntityRepository;

/**
 * BaseRepository
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
class BaseRepository extends EntityRepository
{   
    /**
     * Getting either the option passed in or the default
     * 
     */
    public static function getOption($options, $key, $default = null)
    {
        return isset($options[$key]) ? $options[$key] : $default;
    }
    
    public function getLocaleField($field, $locale)
    {
        $locale = strtolower($locale);
        switch ($locale) {
            
            case 'en':
                $localeField = 'english' . ucfirst($field);
                break;
                
            case 'de':
                $localeField = 'german' . ucfirst($field);
                break;
                
            case 'nl':
            default:
                $localeField = $field;
                break;
        }
        
        return $localeField;
    }
    
    /**
     * {@InheritDoc}
     */
    public function all($options = [])
    {
        $criteria = self::getOption($options, 'where',  []);
        $order    = self::getOption($options, 'order',  null);
        $limit    = self::getOption($options, 'limit',  null);
        $offset   = self::getOption($options, 'offset', null);
        
        return $this->findBy($criteria, $order, $limit, $offset);
    }
    
    /**
     * {@InheritDoc}
     */
    public function find($by = [])
    {
        return $this->findOneBy($by);
    }
    
    public function publishedExpr($fieldPrefix, $expr)
    {
        return $expr->andX(
        
            $expr->andX(
                
                $expr->isNotNull($fieldPrefix . '.publishedAt'),
                $expr->lte($fieldPrefix . '.publishedAt', ':now')
            ),
            $expr->orX(
            
                $expr->isNull($fieldPrefix . '.expiredAt'),
                $expr->gt($fieldPrefix . '.expiredAt', ':now')
            )
        );
    }
}