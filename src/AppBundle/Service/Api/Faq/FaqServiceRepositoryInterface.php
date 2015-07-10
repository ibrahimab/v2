<?php
namespace AppBundle\Service\Api\Faq;
use       AppBundle\Concern\WebsiteConcern;
use       AppBundle\Service\Api\Faq\FaqServiceEntityInterface;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
interface FaqServiceRepositoryInterface
{
    /**
     * Setting website
     *
     * @param WebsiteConcern $seasonConcern
     * @return void
     */
    public function setWebsite(WebsiteConcern $websiteConcern);

    /**
     * Getting website
     *
     * @return integer
     */
    public function getWebsite();

    /**
     * Getting all the FAQ items
     *
     * @return FaqServiceEntityInterface[]
     */
    public function getItems();
}