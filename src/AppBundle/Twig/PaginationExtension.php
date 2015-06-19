<?php
namespace AppBundle\Twig;
use       Symfony\Component\DependencyInjection\ContainerInterface;
use       Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use       Symfony\Component\HttpFoundation\Request;
use       Doctrine\ORM\Tools\Pagination\Paginator;

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
     * @var Paginator
     */
    private $paginator;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var string
     */
    private $locale;

    /**
     * @var string
     */
    private $html;

    /**
     * @var array
     */
    private $filters;

    /**
     * @param ContainerInterface $container
     * @param UrlGeneratorInterface $generator
     */
    public function __construct(ContainerInterface $container, UrlGeneratorInterface $generator)
    {
        $this->container = $container;
        $this->generator = $generator;
        $this->request   = $this->container->get('request');
        $this->locale    = $this->request->getLocale();
        $this->filters   = $this->request->query->get('f', []);
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
    public function set(Paginator $paginator)
    {
        $this->paginator = $paginator;
    }

    /**
     * Getting back Paginator Interface to be iterated over for results
     *
     * @return Paginator
     */
    public function get()
    {
        return $this->paginator;
    }

    /**
     * returns TOTAL results that is being paginated on
     *
     * @return int
     */
    public function count()
    {
        return count($this->paginator);
    }

    /**
     * Render pagination template
     *
     * @return string
     */
    public function render(\Twig_Environment $environment, $refresh = false)
    {
        // return cache if already rendered or if refresh is not requested
        if (null !== $this->html && false === $refresh) {
            return $this->html;
        }

        return $this->html = $environment->render('partials/pagination.html.twig', [

            'last'    => $this->paginator->page['last'],
            'current' => $this->paginator->page['current'],
        ]);
    }

    /**
     * Generate url for pagination button
     *
     * @return string
     */
    public function url($page)
    {
        $query   = ['p' => $page];
        $filters = $this->filters;

        if (count($filters) > 0) {
            $query['f'] = $filters;
        }

        return $this->generator->generate('search_' . $this->locale, $query);
    }

    /**
     * Active class html for active page
     *
     * @return string
     */
    public function active($page)
    {
        return ((int)$this->paginator->page['current'] === (int)$page ? ' class="current"' : '');
    }

    /**
     * Register pagination extension name
     *
     * @return string
     */
    public function getName()
    {
        return 'pagination_extension';
    }
}