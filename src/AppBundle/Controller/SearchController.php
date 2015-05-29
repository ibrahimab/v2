<?php
namespace AppBundle\Controller;
use       AppBundle\Annotation\Breadcrumb;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * SearchController
 *
 * This controller is the new search component for Chalet
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.2
 * @since   0.0.2
 *
 * @Breadcrumb(name="frontpage", title="frontpage", translate=true, path="home")
 * @Breadcrumb(name="search", title="search-book", translate=true, active=true, path="search")
 */
class SearchController extends Controller
{
    /**
     * @Route(path="/zoek-en-boek.php", name="search_nl")
     * @Route(path="/search-and-book.php",  name="search_en")
     */
    public function index()
    {
        $searchService = $this->get('service.api.search');
        $results       = $searchService->build()
                                       ->limit(3)
                                       ->offset(0)
                                       ->results();
        
        return $this->render('search/search.html.twig', [
            'results' => $results,
        ]);
    }
}