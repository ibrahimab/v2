<?php
namespace AppBundle\Twig\Extension;

use       AppBundle\Concern\WebsiteConcern;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 */
trait Helper
{
    /**
     * @param string $link
     * @return string
     */
    public function pdfLink($link)
    {
        return '/chalet/' . $link;
    }

    /**
     * @return WebsiteConcern
     */
    public function website()
    {
        return $this->websiteConcern;
    }

    /**
     * @return boolean
     */
    public function opened()
    {
        return $this->generalSettingsService->opened();
    }

    /**
     * @return boolean
     */
    public function showSunnyCars()
    {
        return $this->websiteConcern->get() === WebsiteConcern::WEBSITE_CHALET_NL;
    }
}