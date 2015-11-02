<?php
namespace AppBundle\Twig\Extension;

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
        if (null === $this->generalSettingsService) {
            $this->generalSettingsService = $this->container->get('app.api.general.settings');
        }

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