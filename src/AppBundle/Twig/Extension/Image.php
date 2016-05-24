<?php
namespace AppBundle\Twig\Extension;

use       AppBundle\Service\Api\Search\Result\Resultset;
use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;
use       AppBundle\Service\Api\Region\RegionServiceEntityInterface;
use       AppBundle\Service\Api\Place\PlaceServiceEntityInterface;
use       AppBundle\Service\Api\Country\CountryServiceEntityInterface;
use       AppBundle\Service\Api\Theme\ThemeServiceEntityInterface;
use       AppBundle\Document\File\Country\CountryFileDocument;
use       AppBundle\Service\Api\File\Type\TypeService                               as TypeFileService;
use       AppBundle\Document\File\Type                                              as TypeFileDocument;
use       AppBundle\Service\Api\File\Accommodation\AccommodationService             as AccommodationFileService;
use       AppBundle\Document\File\Accommodation                                     as AccommodationFileDocument;
use       AppBundle\Service\Api\File\Region\RegionService                           as RegionFileService;
use       AppBundle\Document\File\Region                                            as RegionFileDocument;
use       AppBundle\Document\File\Place                                             as PlaceFileDocument;
use       AppBundle\Service\Api\File\Theme\ThemeService                             as ThemeFileService;
use       AppBundle\Document\File\Theme                                             as ThemeFileDocument;
use       AppBundle\Service\Api\HomepageBlock\HomepageBlockServiceEntityInterface;
use       \InvalidArgumentException;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @author  Jeroen Boschman <jeroen@webtastic.nl>
 * @package Chalet
 */
trait Image
{
    /**
     * Returns old image root directory
     *
     * @return string
     */
    public function getOldImageRoot()
    {
        return $this->oldImageRoot;
    }

    /**
     * Returns old image url prefix
     */
    public function getOldImageUrlPrefix()
    {
        return '/chalet-pic';
    }

    /**
     * Returns old website prefix
     *
     * @return string
     */
    public function getOldSiteUrlPrefix()
    {
        return $this->oldSiteUrlPrefix;
    }

    /**
     * @param string $kind
     * @return FileService
     */
    public function getFileService($kind)
    {
        return $this->fileServices[$kind];
    }

    /**
     * Getting Image url from a Type Entity
     *
     * @param TypeServiceEntityInterface $type
     * @return string
     */
    public function getTypeImage($type)
    {
        if ($type instanceof TypeServiceEntityInterface) {

            $typeId          = $type->getId();
            $accommodationId = $type->getAccommodation()->getId();

        } else {

            $typeId          = intval($type['type_id']);
            $accommodationId = intval($type['accommodation_id']);
        }

        $file = $this->getFileService('type')->getMainImage($typeId);

        if (null === $file) {

            // type image not found
            $file = $this->getFileService('accommodation')->getMainImage($accommodationId);
        }

        return $file;
    }

    /**
     * Getting all the type images from its directory
     *
     * @param TypeServiceEntityInterface $type
     * @param int $above_limit This defines how many images above should be displayed
     * @param int $below_limit This defines how many image below are displayed by default
     * @return []
     */
    public function getTypeImages(TypeServiceEntityInterface $type, $above_limit = 3, $below_limit = 2)
    {
        $accommodationFiles = $this->getFileService('accommodation')->getImages($type->getAccommodation()->getId());
        $typeFiles          = $this->getFileService('type')->getImages($type->getId());
        $images             = $this->getFileService('file')->parse($accommodationFiles);
        $images             = $this->getFileService('file')->parse($typeFiles, $images);

        return $images;
    }

    /**
     * @param Resultset $resultset
     * @return []
     */
    public function getSearchImages(Resultset $resultset)
    {
        $ids = ['types' => [], 'accommodations' => []];
        $results = $resultset->getPaginator();

        foreach ($results as $result) {

            if (false !== ($row = current($result))) {

                if (null !== ($type = $resultset->getCheapestRow($row['group_id']))) {

                    $ids['types'][] = $type['type_id'];
                    $ids['accommodations'][$type['type_id']] = $type['accommodation_id'];
                }
            }
        }

        $files  = $this->getFileService('type')->getSearchImages($ids['types']);
        $images = [];
        $mapper = [];

        foreach ($files as $file) {
            $images[$file['file_id']][] = $file;
        }

        $notFound = [];

        foreach ($ids['accommodations'] as $typeId => $accommodationId) {

            if (!array_key_exists($typeId, $images)) {

                $notFound[] = $accommodationId;
                $mapper[$accommodationId] = $typeId;
            }
        }

        $accommodationFiles = [];

        if (count($notFound) > 0) {
            $accommodationFiles = $this->getFileService('accommodation')->getSearchImages($notFound);
        }

        foreach ($accommodationFiles as $file) {

            if (!isset($mapper[$file['file_id']])) {
                continue;
            }

            $typeId = $mapper[$file['file_id']];

            $images[$typeId][] = $file;
        }

        return $images;
    }

    /**
     * Getting Region ski runs map image
     *
     * @param RegionServiceEntityInterface $region
     * @return array
     */
    public function getRegionImage(RegionServiceEntityInterface $region)
    {
        $image = $this->getFileService('region')->getImage($region->getId());
        return $image;
    }

    /**
     * @param RegionServiceEntityInterface $region
     * @return array
     */
    public function getRegionImages(RegionServiceEntityInterface $region)
    {
        $images = $this->getFileService('region')->getImages($region->getId());
        return $images;
    }

    /**
     * Getting Region ski runs map image
     *
     * @param RegionServiceEntityInterface $region
     * @return string
     */
    public function getRegionSkimapImage(RegionServiceEntityInterface $region)
    {
        $image = $this->getFileService('region')->getSkimapImage($region->getId());
        return $image;
    }

    /**
     * Getting Place image
     *
     * @param PlaceServiceEntityInterface $place
     * @return array
     */
    public function getPlaceImage(PlaceServiceEntityInterface $place)
    {
        $image = $this->getFileService('place')->getImage($place->getId());
        return $image;
    }

    /**
     * @param PlaceServiceEntityInterface $place
     * @return array
     */
    public function getPlaceImages(PlaceServiceEntityInterface $place)
    {
        $images = $this->getFileService('place')->getImages($place->getId());
        return $images;
    }

    /**
     * Getting Theme image
     *
     * @param ThemeServiceEntityInterface $theme
     * @return string
     */
    public function getThemeImage($theme)
    {
        $themeId   = ($theme instanceof ThemeServiceEntityInterface ? $theme->getId() : $theme);

        $directory = 'themas_hoofdpagina';
        $filename  = $themeId . '.jpg';
        $path      = $this->getOldImageRoot() . '/cms/' . $directory;
        $file      = $path . '/' . $filename;

        if (!file_exists($file)) {

            $directory = 'accommodaties';
            $filename  = '0.jpg';
        }

        return ['directory' => $directory, 'filename' => $filename];
    }

    /**
     * Getting Homepage block image
     *
     * @param HomepageBlockServiceEntityInterface $homepageBlock
     * @return string|null
     */
    public function getHomepageBlockImage(HomepageBlockServiceEntityInterface $homepageBlock)
    {
        $homepageBlockId = $homepageBlock->getId();
        $file            = $this->getOldImageRoot() . '/cms/homepageblokken/' . $homepageBlockId . '.jpg';
        $filename        = 'homepageblokken/0.jpg';

        if (file_exists($file)) {
            $filename = 'homepageblokken/' . $homepageBlockId . '.jpg';
        }

        return $this->getOldImageUrlPrefix() . '/' . $filename;
    }

    /**
     * @param integer $countryId
     *
     * @return string
     */
    public function getCountryImage($countryId)
    {
        $directory = $this->getOldImageRoot() . '/cms/landen';
        $files     = glob($directory . '/' . $countryId . '-*.jpg');
        $filename  = current($files);
        $filename  = pathinfo($filename, PATHINFO_BASENAME);

        return ['directory' => 'landen', 'filename' => $filename];
    }

    /**
     * @param array $file
     * @return string
     */
    public function generateImagePath($file)
    {
        return $this->getOldImageUrlPrefix() . '/' . (null === $file ? 'accommodaties/0.jpg' : $file['directory'] . '/' . $file['filename']);
    }

    /**
     * Generate path to thumbnail
     *
     * @param string $file
     * @param int $width
     * @param int $height
     * @return string
     */
    public function generateThumbnailPath($file, $width, $height = 0)
    {

        if (strpos($file, $this->getOldImageUrlPrefix()) !== 0) {
            throw new InvalidArgumentException('thumbnails can only be generated for files located in ' . $this->getOldImageUrlPrefix());
        }
        if (!is_int($width) || $width < 1) {
            throw new InvalidArgumentException('width must be an integer higher than 0');
        }
        if (!is_int($height)) {
            throw new InvalidArgumentException('height must be an integer');
        }

        $sourceFile         = str_replace($this->getOldImageUrlPrefix() . '/', '', $file);
        $sourceFileFullPath = $this->getOldImageRoot() . '/cms/' . $sourceFile;

        if ($height === 0) {

            // calculate the height based on the width

            $imgSize = getimagesize($sourceFileFullPath);

            $sourceWidth  = $imgSize[0];
            $sourceHeight = $imgSize[1];

            $height = round($width * $sourceHeight / $sourceWidth);

        }

        $thumbnailFile = '_imgcache/' . $width . 'x' . $height . '-' . str_replace('/', '-', $sourceFile);

        $thumbnailFileFullPath = $this->getOldImageRoot() . '/cms/' . $thumbnailFile;

        if (file_exists($thumbnailFileFullPath)) {
            return $this->getOldImageUrlPrefix() . '/' . $thumbnailFile;
        } else {

            // no thumbnail found: have the old website create it
            return $this->getOldSiteUrlPrefix() . 'thumbnail.php?file=' . urlencode($sourceFile) . '&w=' . $width . '&h=' . $height;

        }
    }
}
