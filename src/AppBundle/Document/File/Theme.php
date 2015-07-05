<?php
namespace AppBundle\Document\File;
use       Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(db="files", collection="themes", repositoryClass="AppBundle\Document\File\ThemeRepository")
 */
class Theme extends File
{
}