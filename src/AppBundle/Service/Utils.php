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
    	$text = $this->normalize($text);
    	$text = preg_replace('/[^A-Za-z0-9_\-]/', '-', $text);
    	$text = preg_replace('/-{2,}/', '-', $text);
    	$text = preg_replace('/-$/', '', $text);
        
    	return urlencode($text);
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