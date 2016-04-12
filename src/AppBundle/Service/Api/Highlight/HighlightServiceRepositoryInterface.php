<?php
namespace AppBundle\Service\Api\Highlight;

use       AppBundle\Concern\SeasonConcern;
use       AppBundle\Concern\WebsiteConcern;

/**
 * HighlightServiceRepositoryInterface
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
interface HighlightServiceRepositoryInterface
{
    /**
     * Get all highlights
     *
     * @param integer $limit
     * @param integer $resultsPerRow
     *
     * @return array
     */
    public function displayable($limit, $resultsPerRow);
}