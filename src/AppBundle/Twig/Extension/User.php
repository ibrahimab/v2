<?php
namespace AppBundle\Twig\Extension;

use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 */
trait User
{
    /**
     * Count favorites
     *
     * @return int
     */
    public function favoritesCount()
    {
        if (null !== ($user = $this->getUser())) {
            return $user->totalFavorites();
        }

        return 0;
    }

    public function isFavorite(TypeServiceEntityInterface $type)
    {
        if (null !== ($user = $this->getUser())) {
            return in_array($type->getId(), $user->getFavorites());
        }

        return false;
    }

    /**
     * Count viewed
     *
     * @return int
     */
    public function viewedCount()
    {
        if (null !== ($user = $this->getUser())) {
            return $user->totalViewed();
        }

        return 0;
    }

    /**
     * Count searches
     *
     * @return int
     */
    public function searchesCount()
    {
        if (null !== ($user = $this->getUser())) {
            return $user->totalSearches();
        }

        return 0;
    }

    /**
     * @return AnonymousToken
     */
    protected function getUser()
    {
        return $this->currentUser;
    }
}