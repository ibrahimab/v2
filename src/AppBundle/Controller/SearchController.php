<?php
namespace AppBundle\Controller;
use       AppBundle\Annotation\Breadcrumb;
use       AppBundle\Service\Api\Search\SearchBuilder;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;
use       Symfony\Component\HttpFoundation\Request;

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
     * @Route(path="/zoek-en-boek.php", name="search_nl", defaults={"p": 1})
     * @Route(path="/search-and-book.php",  name="search_en")
     */
    public function index(Request $request)
    {
        $page          = intval($request->query->get('p'));
        $page          = ($page === 0 ? $page : ($page - 1));
        $per_page      = intval($this->container->getParameter('app')['results_per_page']);
        $offset        = round($per_page * $page);
        
        $searchService = $this->get('service.api.search');
        $results       = $searchService->build()
                                       ->limit($per_page)
                                       ->offset($offset)
                                       ->sort(SearchBuilder::SORT_BY_ACCOMMODATION_NAME, SearchBuilder::SORT_ORDER_ASC)
                                       ->results();
        
        return $this->render('search/search.html.twig', [
            'results' => $results,
        ]);
    }
}