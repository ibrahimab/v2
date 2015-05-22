<?php
namespace AppBundle\Document\File;
use       Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(db="files", collection="regions", repositoryClass="AppBundle\Document\File\RegionRepository")
 */
class Region extends File
{
}