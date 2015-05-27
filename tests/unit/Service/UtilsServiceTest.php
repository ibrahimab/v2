<?php
namespace AppBundle\Tests\Unit\Service;

use       AppBundle\Service\UtilsService;

class UtilsServiceTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var \appTestDebugProjectContainer
     */
    protected $serviceContainer;

    /**
     * @var AppBundle\Service\Javascript\JavascriptService
     */
    protected $utilsService;

    protected function _before()
    {
        $this->utilsService = new UtilsService();
    }

    protected function _after()
    {
        $this->utilsService = null;
    }

	public function testSeo()
	{
		$name    = 'Testing seo means: normalizing this text!';
		$seoName = 'Testing-seo-means-normalizing-this-text';

		$this->assertEquals($seoName, $this->utilsService->seo($name));
	}

	public function testNormalizingText()
	{
		$normalizingText 		 = "ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñš";
		$expectingNormalizedText = 'AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNns';
		$normalizedText			 = $this->utilsService->normalizeText($normalizingText);
		dump(iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $normalizingText));
		$this->assertTrue(strlen($normalizedText) === 54);
		$this->assertEquals($expectingNormalizedText, $normalizedText);
	}
}