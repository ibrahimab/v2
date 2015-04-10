<?php
namespace AppBundle\Service;

class Utils
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
            
    	], ['-', '-', ''], $this->normalize($text)));
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
	public function normalize($text)
    {
		return strtr($text, 'ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñš', 
                            'AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNns');
	}
}