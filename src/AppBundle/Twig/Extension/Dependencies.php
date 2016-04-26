<?php

namespace AppBundle\Twig\Extension;

use AppBundle\Service\Api\Search\Filter\Tokenizer;
use AppBundle\Concern\WebsiteConcern;
use AppBundle\Concern\LocaleConcern;
use AppBundle\Service\Javascript\JavascriptService;
use AppBundle\Service\Api\File\FileService;
use AppBundle\Service\Api\File\Type\TypeService as TypeFileService;
use AppBundle\Service\Api\File\Accommodation\AccommodationService as AccommodationFileService;
use AppBundle\Service\Api\File\Place\PlaceService as PlaceFileService;
use AppBundle\Service\Api\File\Region\RegionService as RegionFileService;
use AppBundle\Service\Api\File\Theme\ThemeService as ThemeFileService;
use AppBundle\Service\Api\GeneralSettings\GeneralSettingsService;
use AppBundle\Service\Api\User\UserService;
use AppBundle\Service\Legacy\CmsUser\CmsUserService;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 */
trait Dependencies
{
    /**
     * @param LocaleConcern $localeConcern
     */
    public function setLocaleConcern(LocaleConcern $localeConcern)
    {
        $this->localeConcern = $localeConcern;
    }

    /**
     * @param Tokenizer $filterTokenizer
     */
    public function setFilterTokenizer(Tokenizer $filterTokenizer)
    {
        $this->filterTokenizer = $filterTokenizer;
    }

    /**
     * @param WebsiteConcern $websiteConcern
     */
    public function setWebsiteConcern(WebsiteConcern $websiteConcern)
    {
        $this->websiteConcern = $websiteConcern;
    }

    /**
     * @param UrlGeneratorInterface $generator
     */
    public function setUrlGenerator(UrlGeneratorInterface $generator)
    {
        $this->generator = $generator;
    }

    /**
     * @param JavascriptService $javascriptService
     */
    public function setJavascriptService(JavascriptService $javascriptService)
    {
        $this->javascriptService = $javascriptService;
    }

    /**
     * @param GeneralSettingsService $generalSettingsService
     */
    public function setGeneralSettingsService(GeneralSettingsService $generalSettingsService)
    {
        $this->generalSettingsService = $generalSettingsService;
    }

    /**
     * @param string $oldImageRoot
     */
    public function setOldImageRoot($oldImageRoot)
    {
        $this->oldImageRoot = $oldImageRoot;
    }

    /**
     * @param string $urlSiteUrlPrefix
     */
    public function setOldSiteUrlPrefix($oldSiteUrlPrefix)
    {
        $this->oldSiteUrlPrefix = $oldSiteUrlPrefix;
    }

    /**
     * @param CmsUserService $cmsUserService
     */
    public function setCmsUserService(CmsUserService $cmsUserService)
    {
        $this->cmsUserService = $cmsUserService;
    }

    /**
     * @param FileService $file
     * @param TypeFileService $type
     * @param AccommodationFileService $accommodation
     * @param RegionFileService $region
     * @param PlaceFileService $place
     * @param ThemeFileService $theme
     */
    public function setFileServices($file, $type, $accommodation, $region, $place, $theme)
    {
        $this->fileServices['file']          = $file;
        $this->fileServices['type']          = $type;
        $this->fileServices['accommodation'] = $accommodation;
        $this->fileServices['region']        = $region;
        $this->fileServices['place']         = $place;
        $this->fileServices['theme']         = $theme;
    }

    /**
     * @param RequestStack $requestStack
     */
    public function setRequestStack(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @param UserService $userService
     */
    public function setUser(UserService $userService)
    {
        $this->currentUser = $userService->user();
    }
}
