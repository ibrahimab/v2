<?php
namespace AppBundle\Twig;
use       Symfony\Component\DependencyInjection\ContainerInterface;
use       Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * PaginationExtension
 *
 * Pagination extension which will generate the pagination view block
 *
 * @author  Ibrahim Abdullah
 * @package Chalet
 * @version 0.0.2
 * @since   0.0.2
 */
class PaginationExtension extends \Twig_Extension
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var UrlGeneratorInterface
     */
    private $generator;
    
    /**
     * @var 
     */
    private $paginator;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container, UrlGeneratorInterface $generator)
    {
        $this->container   = $container;
        $this->generator   = $generator;
		$this->currentUser = null;
    }
    
    /**
     * Registering functions
     *
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('set_paginator',          [$this, 'set']),
            new \Twig_SimpleFunction('get_paginator',          [$this, 'get']),
            new \Twig_SimpleFunction('count_paginator',        [$this, 'count']),
            new \Twig_SimpleFunction('render_paginator',       [$this, 'render'], ['is_safe' => ['html'], 'needs_environment' => true]),
            new \Twig_SimpleFunction('url_paginator',          [$this, 'url']),
            new \Twig_SimpleFunction('active_class_paginator', [$this, 'active'], ['is_safe' => ['html']]),
        ];
    }
    
    /**
     * Setting paginator instance
     *
     * @param  $paginator
     * @return 
     */
    public function set($paginator)
    {
        $this->paginator = $paginator;
    }
    
    public function get()
    {
        return $this->paginator;
    }
    
    public function count()
    {
        return count($this->paginator);
    }
    
    public function render(\Twig_Environment $environment)
    {
        return $environment->render('partials/pagination.html.twig', [
            
            'last'    => $this->paginator->page['last'],
            'current' => $this->paginator->page['current'],
        ]);
    }
    
    public function url($page)
    {
        $locale = $this->container->get('request')->getLocale();
        return $this->generator->generate('search_' . $locale, ['p' => $page]);
    }
    
    public function active($page)
    {
        return ((int)$this->paginator->page['current'] === (int)$page ? ' class="current"' : '');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pagination_extension';
    }
}