<?php
namespace AppBundle\Twig\Extension;

use AppBundle\Concern\WebsiteConcern;
use Jenssegers\Date\Date;

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

    /**
     * @param string $format
     *
     * @return string
     */
    public function formatDate(\DateTime $date, $format)
    {
        $date = new Date($date->getTimestamp());
        $date->setLocale($this->localeConcern->get());

        return $date->format($format);
    }

    /**
     * count number of fields in an array that are non empty
     *
     * @return void
     * @author
     **/
    public function countArrayValuesNonEmpty($array)
    {
        return count(array_filter($array));
    }
}
