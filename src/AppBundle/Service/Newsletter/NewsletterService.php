<?php
namespace AppBundle\Service\Newsletter;

use       AppBundle\Concern\WebsiteConcern;

/**
 * Service to provide the fieldnames for the Blinker Newsletter forms
 *
 * @author  Jeroen Boschman <jeroen@webtastic.nl>
 * @package Chalet
 * @since   0.0.1
 */
class NewsletterService
{
    /**
     * Main internal storage
     *
     * @var ArrayObject
     */

    private $blinkerFieldNames = array(

        WebsiteConcern::WEBSITE_CHALET_NL => array(

            'email' => "field1038",
            'voornaam' => "field1040",
            'tussenvoegsel' => "field1041",
            'achternaam' => "field1036",
            'per_wanneer' => "field10934",
            'formEncId' => "MwJLgCnDPkS9LWs",
            'formname' => "form1004",
            'subscribeAfterSeason' => true,
        ),

        WebsiteConcern::WEBSITE_CHALET_BE => array(

            'email' => "field10946",
            'voornaam' => "field10947",
            'tussenvoegsel' => "field10948",
            'achternaam' => "field10949",
            'per_wanneer' => "field10950",
            'formEncId' => "mfR7fLLhFtS3ppW",
            'formname' => "form10157",
            'subscribeAfterSeason' => true,
            ),

        WebsiteConcern::WEBSITE_ITALISSIMA_NL => array(

            'email' => "field10751",
            'voornaam' => "field10761",
            'tussenvoegsel' => "field10771",
            'achternaam' => "field10781",
            'formEncId' => "J4XyB4nwd7v3yi8",
            'formname' => "form10081",
            'subscribeAfterSeason' => false,
        ),

        WebsiteConcern::WEBSITE_ITALISSIMA_BE => array(

            'email' => "field10952",
            'voornaam' => "field10953",
            'tussenvoegsel' => "field10954",
            'achternaam' => "field10955",
            'formEncId' => "HN6Txi7YfZvamjD",
            'formname' => "form10158",
            'subscribeAfterSeason' => false,
        )
    );

    /**
     * Injecting WebsiteConcern
     *
     * @param WebsiteConcern $websiteConcern
     */
    public function __construct(WebsiteConcern $websiteConcern)
    {
        $this->websiteConcern = $websiteConcern;
    }

    /**
     * Get requested attribute or throw an exception if not found
     *
     * @param string $attribute
     * @return mixed
     * @throws NotFoundException
     */
    public function get($identifier)
    {
        if (isset($this->blinkerFieldNames[$this->websiteConcern->get()][$identifier])) {
            return $this->blinkerFieldNames[$this->websiteConcern->get()][$identifier];
        }
    }
}
