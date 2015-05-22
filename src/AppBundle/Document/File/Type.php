<?php
namespace AppBundle\Document\File;
use       Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(db="files", collection="types", repositoryClass="AppBundle\Document\File\TypeRepository")
 */
class Type extends File
{
}