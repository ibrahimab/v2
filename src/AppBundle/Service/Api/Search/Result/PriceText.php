<?php
namespace AppBundle\Service\Api\Search\Result;
use       AppBundle\Service\Api\Search\Result\PriceTextType;

/**
 * get the text to show around the price on the search results page
 *
 * @author  Jeroen Boschman <jeroen@webtastic.nl>
 * @package Chalet
 **/
class PriceText
{

    /**
     * get the text to show before the price
     *
     * @return string
     **/
    public function getTextBeforePrice($priceTextType) {

        switch ($priceTextType) {

            case PriceTextType::PRICE_TEXT_ARRANGEMENT_FROM:
                return '1 week vanaf';
                break;

            case PriceTextType::PRICE_TEXT_ARRANGEMENT:
                return '1 week';
                break;

            case PriceTextType::PRICE_TEXT_ACCOMMODATION_FROM:
                return '1 week vanaf';
                break;

            case PriceTextType::PRICE_TEXT_ACCOMMODATION:
                return '1 week';
                break;
        }
    }

    /**
     * get the text to show after the price
     *
     * @return string
     **/
    public function getTextAfterPrice($priceTextType) {

        switch ($priceTextType) {

            case PriceTextType::PRICE_TEXT_ARRANGEMENT_FROM:
                return 'per persoon';
                break;

            case PriceTextType::PRICE_TEXT_ARRANGEMENT:
                return 'per persoon';
                break;

            case PriceTextType::PRICE_TEXT_ACCOMMODATION_FROM:
                return 'per accommodatie';
                break;

            case PriceTextType::PRICE_TEXT_ACCOMMODATION:
                return 'per accommodatie';
                break;
        }
    }

    /**
     * does the price include a skipass?
     *
     * @return boolean
     **/
    public function hasSkiPass($priceTextType) {

        switch ($priceTextType) {

            case PriceTextType::PRICE_TEXT_ARRANGEMENT_FROM:
            case PriceTextType::PRICE_TEXT_ARRANGEMENT:
                return true;
                break;

            default:
                return false;
                break;
        }
    }
}
