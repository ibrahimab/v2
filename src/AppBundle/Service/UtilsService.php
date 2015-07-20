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
    public static function seo($text)
    {
    	return urlencode(preg_replace([

            '/[^A-Za-z0-9_\-]/',
            '/-{2,}/',
            '/-$/',

    	], ['-', '-', ''], $this->normalizeText($text)));
    }

    public static function bbcode($text)
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
	public static function normalizeText($text)
    {
		return strtr($text, [

		    'Š' => 'S', 'š' => 's', 'Ð' => 'Dj','Ž' => 'Z', 'ž' => 'z',  'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A',
		    'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',  'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I',
		    'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O',  'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U',
		    'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a',
		    'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e',  'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i',
		    'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o',  'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u',
		    'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ý' => 'y', 'ý' => 'y', 'þ' => 'b',  'ÿ' => 'y', 'ƒ' => 'f',
		    'ă' => 'a', 'î' => 'i', 'â' => 'a', 'ș' => 's', 'ț' => 't',  'Ă' => 'A', 'Î' => 'I', 'Â' => 'A', 'Ș' => 'S', 'Ț' => 'T',
		]);
	}

	/**
	 * Normalize controller in a more readable format
	 *
	 * @param string $controller
	 * @return string
	 */
	public static function normalizeController($controller)
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
    
    /**
     * Generating a random token
     *
     * @param string $seed
     * @param int $length
     * @return string
     */
    public static function generateToken($seed, $length = 30)
    {
        $characters = array_merge(range(0, 9), range('a', 'z'), range('A', 'F'), str_split(sha1($seed) . time()));
        $total      = count($characters);
        $token      = '';
        $done       = 0;
        
        while ($done++ < $length) {
            $token .= $characters[mt_rand(0, $total - 1)];
        }
        
        return $token;
    }
}