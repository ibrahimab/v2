<?php
namespace AppBundle\Controller;

use       AppBundle\Annotation\Breadcrumb;
use       AppBundle\Service\Api\HomepageBlock\HomepageBlockServiceEntityInterface;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * PagesController
 *
 * This controller handles all the normal pages
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @since   0.0.1
 *
 * @Breadcrumb(name="frontpage", title="frontpage", translate=true, path="home")
 */
class PagesController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Template(":pages:home.html.twig")
     */
    public function home()
    {
        $config               = $this->container->getParameter('app');
        $surveyService        = $this->get('service.api.booking.survey');
        $highlightService     = $this->get('service.api.highlight');
        $homepageBlockService = $this->get('service.api.homepageblock');
        
        $homepageBlocks   = $homepageBlockService->published();
        $highlights       = $highlightService->displayable(['limit' => $config['service']['api']['highlight']['limit']]);
        $types            = [];
        
        foreach ($highlights as $highlight) {
            
            $type                  = $highlight->getType();
            $types[$type->getId()] = $type;
        }
        
        $surveyStats = $surveyService->statsByTypes($types);
        foreach ($surveyStats as $surveyStat) {
            
            $types[$surveyStat['typeId']]->setSurveyCount($surveyStat['surveyCount']);
            $types[$surveyStat['typeId']]->setSurveyAverageOverallRating($surveyStat['surveyAverageOverallRating']);
        }
        
        $groupedHomepageBlocks = ['left' => [], 'right' => []];
        foreach ($homepageBlocks as $block) {
            
            if ($block->getPosition() === HomepageBlockServiceEntityInterface::POSITION_LEFT) {
                $groupedHomepageBlocks['left'][] = $block;
            }
            
            if ($block->getPosition() === HomepageBlockServiceEntityInterface::POSITION_RIGHT) {
                $groupedHomepageBlocks['right'][] = $block;
            }
        }
        
        return [
            
            'highlights'     => $highlights,
            'homepageBlocks' => $groupedHomepageBlocks,
        ];
    }
    
    /**
     * @Route("/over-ons", name="page_about_nl")
     * @Route("/about-us", name="page_about_en")
     * @Breadcrumb(name="about", title="about", translate=true, active=true)
     * @Template(":pages:about.html.twig")
     */
    public function about()
    {
        return [];
    }

    /**
     * @Route("/verzekeringen", name="page_insurances_nl")
     * @Route("/insurances", name="page_insurances_en")
     * @Breadcrumb(name="insurances", title="insurances", translate=true, active=true)
     * @Template(":pages:insurances.html.twig")
     */
    public function insurances()
    {
        return [];
    }

    /**
     * @Route("/veel-gestelde-vragen", name="page_faq_nl")
     * @Route("/frequently-asked-questions", name="page_faq_en")
     * @Breadcrumb(name="faq", title="faq", translate=true, active=true)
     * @Template(":pages:faq.html.twig")
     */
    public function faq()
    {
        return [];
    }

    /**
     * @Route("/algemene-voorwaarden", name="page_terms_nl")
     * @Route("/terms", name="page_terms_en")
     * @Breadcrumb(name="terms", title="terms", translate=true, active=true)
     * @Template(":pages:terms.html.twig")
     */
    public function terms()
    {
        return [];
    }

    /**
     * @Route("/disclaimer", name="page_disclaimer")
     * @Breadcrumb(name="disclaimer", title="disclaimer", translate=true, active=true)
     * @Template(":pages:disclaimer.html.twig")
     */
    public function disclaimer()
    {
        return [];
    }

    /**
     * @Route("/privacy", name="page_privacy")
     * @Breadcrumb(name="privacy", title="privacy", translate=true, active=true)
     * @Template(":pages:privacy.html.twig")
     */
    public function privacy()
    {
        return [];
    }
}
