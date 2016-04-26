<?php
namespace AppBundle\Service\Legacy\CmsUser;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 */
interface RepositoryInterface
{
    /**
     * @param integer $userId
     *
     * @return array
     */
    public function getUser($userId);
}
