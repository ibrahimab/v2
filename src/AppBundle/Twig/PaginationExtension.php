<?php
namespace AppBundle\Twig;

use AppBundle\Concern\LocaleConcern;
use AppBundle\Service\Api\Search\Result\Paginator\Paginator;
use AppBundle\Service\Api\Search\Params;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Zend\Diactoros\ServerRequestFactory;

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
     * @var Params
     */
    private $params;

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
     * @param LocaleConcern $localeConcern
     * @param UrlGeneratorInterface $generator
     */
    public function __construct(RequestStack $requestStack, LocaleConcern $localeConcern, UrlGeneratorInterface $generator)
    {
        $this->generator = $generator;
        $this->request   = $requestStack->getCurrentRequest();

        if (null !== $this->request) {

            $psrRequest    = ServerRequestFactory::fromGlobals($_SERVER, $this->request->query->all());
            $this->params  = new Params($psrRequest);
            $this->locale  = $localeConcern->get();
            $this->filters = $this->params->getFilters() ?: [];
        }
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
     * @param  PaginatorService $paginator
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
        return $this->paginator->total();
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

            'last'    => ($this->paginator->getTotalPages() + 1),
            'current' => ($this->paginator->getCurrentPage() + 1),
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

        if ($this->request->attributes->get('_route') === 'show_theme_' . $this->locale) {
            $query['url'] = $this->request->attributes->get('url');
        }

        return $this->generator->generate($this->request->attributes->get('_route'), $query);
    }

    /**
     * Active class html for active page
     *
     * @return string
     */
    public function active($page)
    {
        return (($this->paginator->getCurrentPage() + 1) === (int)$page ? ' class="current"' : '');
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
