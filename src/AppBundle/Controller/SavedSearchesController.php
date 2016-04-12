<?php
namespace AppBundle\Controller;

use AppBundle\Annotation\Breadcrumb;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 *
 * @Breadcrumb(name="frontpage", title="frontpage", translate=true, path="home")
 */
class SavedSearchesController extends Controller
{
    /**
     * @Route("/zoekopdrachten", name="page_searches_nl")
     * @Route("/searches", name="page_searches_en")
     * @Breadcrumb(name="searches", title="page-searches", translate=true, active=true)
     */
    public function show()
    {
        $searches = $this->container->get('app.api.user')->user()->getSearches();
        $saved    = [];

        foreach ($searches as $search) {

            $hasSearch = false;

            foreach ($search['search'] as $item) {

                if ($item !== false) {
                    $hasSearch = true;
                }
            }

            if (false === $hasSearch) {
                continue;
            }

            if ($search['search']['w']) {
                $search['search']['w'] = strftime('%e %B %Y', $search['search']['w']);
            }

            $saved[] = $search;
        }

        return $this->render('saved_searches/show.html.twig', [
            'saved_searches' => $saved,
        ]);
    }

    /**
     * @Route("/searches/delete/{id}", name="delete_saved_search")
     */
    public function delete($id)
    {
        $locale = $this->get('app.concern.locale')->get();
        $user   = $this->get('app.api.user');
        $user->removeSearch($id);

        return $this->redirectToRoute('page_searches_' . $locale);
    }

    /**
     * @Route("/searches/clear", name="clear_saved_searches")
     */
    public function clear()
    {
        $locale = $this->get('app.concern.locale')->get();
        $user   = $this->get('app.api.user');
        $user->clearSearches();

        return $this->redirectToRoute('page_searches_' . $locale);
    }
}