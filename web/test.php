<?php
include_once '../vendor/autoload.php';

use Symfony\Component\Finder\Finder;

$finder = new Finder();
$dir = '/var/www/old-website/pic/cms/';
$ret = $finder->files()
              ->in([$dir . 'types', $dir . 'types_specifiek', $dir . 'types_specifiek_tn', $dir . 'hoofdfoto_type', $dir . 'types_breed'])
              ->depth('== 0')
              ->name('/^10(-[0-9]+)?\.jpg/i');
              var_dump(iterator_count($ret));
foreach ($ret as $file) {
    var_dump(basename($file->getPath()));break;//getRelativePath());
}

exit;