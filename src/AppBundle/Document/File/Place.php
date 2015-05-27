<?php
namespace AppBundle\Document\File;
use       Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(db="files", collection="places", repositoryClass="AppBundle\Document\File\PlaceRepository")
 */
class Place extends File
{
}