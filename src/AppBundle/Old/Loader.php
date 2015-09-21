<?php
namespace AppBundle\Old;
use       AppBundle\Concern\WebsiteConcern;

class Loader
{
    public static function loadTxt($locale, WebsiteConcern $website)
    {
        $path = (dirname(dirname(__DIR__))) . '/old-classes';
        $path = readlink($path);
        $path = dirname(dirname($path));

        $GLOBALS['taal']        = $locale;
        $GLOBALS['websitetype'] = $website->type();
        $GLOBALS['website']     = $website->get();
        $GLOBALS['websiteland'] = $website->country();
        $GLOBALS['website']     = ['type' => $website->type(), 'website' => $website->get(), 'country' => $website->country()];
        
        $vars = ['websitetype' => $website->type(), 'websitenaam' => $website->name(), 'ttv' => ($locale === 'nl' ? '' : '_' . $locale)];
        
    	include $path . '/content/_teksten.php';
        include $path . '/content/_teksten_intern.php';

        $GLOBALS['txt']  = $txt;
        $GLOBALS['txta'] = $txta;

        include dirname(dirname(dirname(__DIR__))) . '/app/bc_functions.php';
    }
}