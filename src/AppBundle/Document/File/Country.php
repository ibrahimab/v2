<?php
namespace AppBundle\Document\File;
use       Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(db="files", collection="countries", repositoryClass="AppBundle\Document\File\CountryRepository")
 */
class Country extends File
{
}