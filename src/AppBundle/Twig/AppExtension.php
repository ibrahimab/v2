<?php
namespace AppBundle\Twig;

use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;
use       Symfony\Component\DependencyInjection\ContainerInterface;

class AppExtension extends \Twig_Extension
{
    
    /**
     * @var ContainerInterface
     */
    private $container;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    public function getFunctions()
    {
        return [
            'image_url' => new \Twig_Function_Method($this, 'imageUrl'),
        ];
    }
    
    public function imageUrl(TypeServiceEntityInterface $type) {
        
        $path  = dirname($this->container->get('kernel')->getRootDir()) . '/web/chalet/pic/cms/';
        $file  = 'accommodaties/0';
        $cache = 'pic/cms/';

        if (file_exists($path . 'hoofdfoto_type/' . $type->getId() . '.jpg')) {
            $file = 'hoofdfoto_type/' . $type->getId();
        } elseif (file_exists($path . 'hoofdfoto_accommodatie/' . $type->getAccommodation()->getId() . '.jpg')) {
            $file = 'hoofdfoto_accommodatie/' . $type->getAccommodation()->getId();
        }
        
        return '/chalet/' . $cache . $file . '.jpg';
    }
    
    public function getName()
    {
        return 'app_extension';
    }
}