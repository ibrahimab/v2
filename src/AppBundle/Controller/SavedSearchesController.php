<?php
namespace AppBundle\Controller;

use AppBundle\Annotation\Breadcrumb;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Jenssegers\Date\Date;

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
        $searches = $this->get('app.api.user')->user()->getSearches();
        $locale   = $this->get('app.concern.locale')->get();
        $saved    = [];

        $weekend  = new Date();
        $weekend->setLocale($locale);

        foreach ($searches as $search) {

            $hasSearch = false;

            $search['search_params'] = [];

            foreach ($search['search'] as $key => $item) {

                if ($item !== false) {

                    $hasSearch = true;
                    $search['search_params'][$key] = $item;
                }
            }

            if (false === $hasSearch) {
                continue;
            }

            if ($search['search']['w']) {

                $weekend->setTimestamp($search['search']['w']);
                $search['search']['w'] = $weekend->format('d F Y');
            }

            $saved[$search['created_at']->toDateTime()->getTimestamp()] = $search;
        }

        krsort($saved);

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