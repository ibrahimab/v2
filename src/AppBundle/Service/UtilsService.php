<?php
namespace AppBundle\Service;

class UtilsService
{
    /**
     * Return seo version of text
     *
     * @param string $text
     * @return string
     */
    public function seo($text)
    {
    	return urlencode(preg_replace([

            '/[^A-Za-z0-9_\-]/',
            '/-{2,}/',
            '/-$/',

    	], ['-', '-', ''], $this->normalizeText($text)));
    }

    public function bbcode($text)
    {
        return preg_replace([

            '/\[([bi])\](.+?)\[\/\1\]/i',
            '/\[(link)=((?:http:\/\/|www)?[^\/]+?)\](.+?)\[\/\1\]/i',
            '/\[(link)=([\/].+?)\](.+?)\[\/\1\]/i',

        ], [

            '<$1>$2</$1>',
            '<a href="$2" data-role="new-window">$3</a>',
            '<a href="$2">$3</a>',

        ], $text);
    }

    /**
     * Normalize text, transforming UTF-8 characters to ASCII
     *
     * @param string $text
     * @return string
     */
	public function normalizeText($text)
    {
		return strtr($text, 'ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñš',
                            'AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNns');
	}

	/**
	 * Normalize controller in a more readable format
	 *
	 * @param string $controller
	 * @return string
	 */
	public function normalizeController($controller)
	{
		// if controller is called without the AppBundle namespace in it, just return full controller
		if (false === strpos($controller, 'AppBundle')) {
			return $controller;
		}

		// full controller name is in the form of AppBundle\Controller\<controller path(s)>::<action>
		// so we split it up and format it into the last known controller in the controller path(s)
		// and with action behind it => <LastController>::<action>
		list($namespace, $normalizingController) = explode('::', $controller);
		$names 						  			 = explode('\\', $namespace);

		return strtolower(str_replace('Controller', '', array_pop($names)) . '::' . $normalizingController);
	}
}