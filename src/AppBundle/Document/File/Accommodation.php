<?php
namespace AppBundle\Document\File;
use       Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(db="files", collection="accommodations", repositoryClass="AppBundle\Document\File\AccommodationRepository")
 */
class Accommodation extends File
{
}