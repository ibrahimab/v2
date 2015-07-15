<?php
namespace AppBundle\Controller;
use       AppBundle\Concern\SeasonConcern;
use       AppBundle\Concern\WebsiteConcern;
use       AppBundle\Annotation\Breadcrumb;
use       AppBundle\Service\Api\HomepageBlock\HomepageBlockServiceEntityInterface;
use       AppBundle\Service\Api\Region\RegionServiceEntityInterface;
use       AppBundle\Service\Api\Autocomplete\AutocompleteService;
use       AppBundle\Service\FilterService;
use       AppBundle\Service\Api\Search\SearchBuilder;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;
use       Symfony\Component\HttpFoundation\Request;

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
$content =
'{
  "ref": "refs/heads/development",
  "before": "9d49349c615636b7528ab845ff2613760fbfdf29",
  "after": "12a76bbc08ef652bf1d470bc15914c645995c520",
  "created": false,
  "deleted": false,
  "forced": false,
  "base_ref": null,
  "compare": "https://github.com/Chalet/chalet-v2/compare/9d49349c6156...12a76bbc08ef",
  "commits": [
    {
      "id": "12a76bbc08ef652bf1d470bc15914c645995c520",
      "distinct": true,
      "message": "added payload to signature validator",
      "timestamp": "2015-07-15T16:13:43+02:00",
      "url": "https://github.com/Chalet/chalet-v2/commit/12a76bbc08ef652bf1d470bc15914c645995c520",
      "author": {
        "name": "Ibrahim Abdullah",
        "email": "abdul.ibrahim16@gmail.com",
        "username": "ibrahimab"
      },
      "committer": {
        "name": "Ibrahim Abdullah",
        "email": "abdul.ibrahim16@gmail.com",
        "username": "ibrahimab"
      },
      "added": [

      ],
      "removed": [

      ],
      "modified": [
        "src/AppBundle/Security/Access/Validator/Github.php"
      ]
    }
  ],
  "head_commit": {
    "id": "12a76bbc08ef652bf1d470bc15914c645995c520",
    "distinct": true,
    "message": "added payload to signature validator",
    "timestamp": "2015-07-15T16:13:43+02:00",
    "url": "https://github.com/Chalet/chalet-v2/commit/12a76bbc08ef652bf1d470bc15914c645995c520",
    "author": {
      "name": "Ibrahim Abdullah",
      "email": "abdul.ibrahim16@gmail.com",
      "username": "ibrahimab"
    },
    "committer": {
      "name": "Ibrahim Abdullah",
      "email": "abdul.ibrahim16@gmail.com",
      "username": "ibrahimab"
    },
    "added": [

    ],
    "removed": [

    ],
    "modified": [
      "src/AppBundle/Security/Access/Validator/Github.php"
    ]
  },
  "repository": {
    "id": 32382167,
    "name": "chalet-v2",
    "full_name": "Chalet/chalet-v2",
    "owner": {
      "name": "Chalet",
      "email": null
    },
    "private": true,
    "html_url": "https://github.com/Chalet/chalet-v2",
    "description": "",
    "fork": false,
    "url": "https://github.com/Chalet/chalet-v2",
    "forks_url": "https://api.github.com/repos/Chalet/chalet-v2/forks",
    "keys_url": "https://api.github.com/repos/Chalet/chalet-v2/keys{/key_id}",
    "collaborators_url": "https://api.github.com/repos/Chalet/chalet-v2/collaborators{/collaborator}",
    "teams_url": "https://api.github.com/repos/Chalet/chalet-v2/teams",
    "hooks_url": "https://api.github.com/repos/Chalet/chalet-v2/hooks",
    "issue_events_url": "https://api.github.com/repos/Chalet/chalet-v2/issues/events{/number}",
    "events_url": "https://api.github.com/repos/Chalet/chalet-v2/events",
    "assignees_url": "https://api.github.com/repos/Chalet/chalet-v2/assignees{/user}",
    "branches_url": "https://api.github.com/repos/Chalet/chalet-v2/branches{/branch}",
    "tags_url": "https://api.github.com/repos/Chalet/chalet-v2/tags",
    "blobs_url": "https://api.github.com/repos/Chalet/chalet-v2/git/blobs{/sha}",
    "git_tags_url": "https://api.github.com/repos/Chalet/chalet-v2/git/tags{/sha}",
    "git_refs_url": "https://api.github.com/repos/Chalet/chalet-v2/git/refs{/sha}",
    "trees_url": "https://api.github.com/repos/Chalet/chalet-v2/git/trees{/sha}",
    "statuses_url": "https://api.github.com/repos/Chalet/chalet-v2/statuses/{sha}",
    "languages_url": "https://api.github.com/repos/Chalet/chalet-v2/languages",
    "stargazers_url": "https://api.github.com/repos/Chalet/chalet-v2/stargazers",
    "contributors_url": "https://api.github.com/repos/Chalet/chalet-v2/contributors",
    "subscribers_url": "https://api.github.com/repos/Chalet/chalet-v2/subscribers",
    "subscription_url": "https://api.github.com/repos/Chalet/chalet-v2/subscription",
    "commits_url": "https://api.github.com/repos/Chalet/chalet-v2/commits{/sha}",
    "git_commits_url": "https://api.github.com/repos/Chalet/chalet-v2/git/commits{/sha}",
    "comments_url": "https://api.github.com/repos/Chalet/chalet-v2/comments{/number}",
    "issue_comment_url": "https://api.github.com/repos/Chalet/chalet-v2/issues/comments{/number}",
    "contents_url": "https://api.github.com/repos/Chalet/chalet-v2/contents/{+path}",
    "compare_url": "https://api.github.com/repos/Chalet/chalet-v2/compare/{base}...{head}",
    "merges_url": "https://api.github.com/repos/Chalet/chalet-v2/merges",
    "archive_url": "https://api.github.com/repos/Chalet/chalet-v2/{archive_format}{/ref}",
    "downloads_url": "https://api.github.com/repos/Chalet/chalet-v2/downloads",
    "issues_url": "https://api.github.com/repos/Chalet/chalet-v2/issues{/number}",
    "pulls_url": "https://api.github.com/repos/Chalet/chalet-v2/pulls{/number}",
    "milestones_url": "https://api.github.com/repos/Chalet/chalet-v2/milestones{/number}",
    "notifications_url": "https://api.github.com/repos/Chalet/chalet-v2/notifications{?since,all,participating}",
    "labels_url": "https://api.github.com/repos/Chalet/chalet-v2/labels{/name}",
    "releases_url": "https://api.github.com/repos/Chalet/chalet-v2/releases{/id}",
    "created_at": 1426582823,
    "updated_at": "2015-06-19T09:06:11Z",
    "pushed_at": 1436969637,
    "git_url": "git://github.com/Chalet/chalet-v2.git",
    "ssh_url": "git@github.com:Chalet/chalet-v2.git",
    "clone_url": "https://github.com/Chalet/chalet-v2.git",
    "svn_url": "https://github.com/Chalet/chalet-v2",
    "homepage": null,
    "size": 5668,
    "stargazers_count": 0,
    "watchers_count": 0,
    "language": "PHP",
    "has_issues": true,
    "has_downloads": true,
    "has_wiki": true,
    "has_pages": false,
    "forks_count": 0,
    "mirror_url": null,
    "open_issues_count": 0,
    "forks": 0,
    "open_issues": 0,
    "watchers": 0,
    "default_branch": "master",
    "stargazers": 0,
    "master_branch": "master",
    "organization": "Chalet"
  },
  "pusher": {
    "name": "ibrahimab",
    "email": "abdul.ibrahim16@gmail.com"
  },
  "organization": {
    "login": "Chalet",
    "id": 1713818,
    "url": "https://api.github.com/orgs/Chalet",
    "repos_url": "https://api.github.com/orgs/Chalet/repos",
    "events_url": "https://api.github.com/orgs/Chalet/events",
    "members_url": "https://api.github.com/orgs/Chalet/members{/member}",
    "public_members_url": "https://api.github.com/orgs/Chalet/public_members{/member}",
    "avatar_url": "https://avatars.githubusercontent.com/u/1713818?v=3",
    "description": null
  },
  "sender": {
    "login": "ibrahimab",
    "id": 26875,
    "avatar_url": "https://avatars.githubusercontent.com/u/26875?v=3",
    "gravatar_id": "",
    "url": "https://api.github.com/users/ibrahimab",
    "html_url": "https://github.com/ibrahimab",
    "followers_url": "https://api.github.com/users/ibrahimab/followers",
    "following_url": "https://api.github.com/users/ibrahimab/following{/other_user}",
    "gists_url": "https://api.github.com/users/ibrahimab/gists{/gist_id}",
    "starred_url": "https://api.github.com/users/ibrahimab/starred{/owner}{/repo}",
    "subscriptions_url": "https://api.github.com/users/ibrahimab/subscriptions",
    "organizations_url": "https://api.github.com/users/ibrahimab/orgs",
    "repos_url": "https://api.github.com/users/ibrahimab/repos",
    "events_url": "https://api.github.com/users/ibrahimab/events{/privacy}",
    "received_events_url": "https://api.github.com/users/ibrahimab/received_events",
    "type": "User",
    "site_admin": false
  }
}';
        $github  = $this->get('app.security.access.validator.github');
        $request = new Request([], [], [], [], [], [], $content);
        $request->headers->set('X-HUB-SIGNATURE', 'sha1=373b6a03fe7a9c480aea66cadd7aafa2aaecfbec');
        $request->setMethod('post');
        $v = $github->validate($request);
        dump($v);
        exit;
        $config               = $this->container->getParameter('app');
        $surveyService        = $this->get('app.api.booking.survey');
        $highlightService     = $this->get('app.api.highlight');
        $homepageBlockService = $this->get('app.api.homepageblock');
        $regionService        = $this->get('app.api.region');
        $placeService         = $this->get('app.api.place');
        $typeService          = $this->get('app.api.type');
        $priceService         = $this->get('app.api.price');
        $regionService        = $this->get('app.api.region');
        $seasonService        = $this->get('app.api.season');
        $searchService        = $this->get('app.api.search');
        $searchBuilder        = $searchService->build()
                                              ->where(SearchBuilder::WHERE_WEEKEND_SKI, 0);

        $regions              = $regionService->findHomepageRegions(['limit' => 1]);
        $places               = [];
        $region               = null;
        $offers               = [];

        if (count($regions) > 0) {

            $region      = $regions[0];
            $places      = $placeService->findHomepagePlaces($region, ['limit' => 3]);
            $regionCount = $typeService->countByRegion($region);
            $region->setTypesCount(array_sum($regionCount))
                   ->setPlacesCount(count($regionCount));

            foreach ($places as $place) {

                if (isset($regionCount[$place->getId()])) {
                    $place->setTypesCount($regionCount[$place->getId()]);
                }
            }
        }

        $homepageBlocks       = $homepageBlockService->published();
        $highlights           = $highlightService->displayable(['limit' => $config['service']['api']['highlight']['limit']]);
        $types                = [];

        foreach ($highlights as $highlight) {

            $type                  = $highlight->getType();
            $types[$type->getId()] = $type;
        }

        if (count($types) > 0) {

            $surveyStats = $surveyService->statsByTypes($types);
            foreach ($surveyStats as $surveyStat) {

                $types[$surveyStat['typeId']]->setSurveyCount($surveyStat['surveyCount']);
                $types[$surveyStat['typeId']]->setSurveyAverageOverallRating($surveyStat['surveyAverageOverallRating']);
            }

            $offers = $priceService->offers(array_keys($types));
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

            'region'         => $region,
            'places'         => $places,
            'highlights'     => $highlights,
            'homepageBlocks' => $groupedHomepageBlocks,
            'offers'         => $offers,
            'weekends'       => $seasonService->weekends($seasonService->seasons()),
            'accommodations' => $searchBuilder->count(),
            'regions'        => $regionService->count(),
        ];
    }

    /**
     * @Route("/wie-zijn-wij.php", name="page_about_nl")
     * @Route("/about-us.php", name="page_about_en")
     * @Breadcrumb(name="about", title="about", translate=true, active=true)
     */
    public function about(Request $request)
    {
        return $this->render('pages/about/' . $request->getLocale() . '.html.twig');
    }

    /**
     * @Route("/verzekeringen.php", name="page_insurances_nl")
     * @Route("/insurance.php", name="page_insurances_en")
     * @Breadcrumb(name="insurances", title="insurances", translate=true, active=true)
     */
    public function insurances(Request $request)
    {
        $seasonService   = $this->get('app.api.season');
        $optionService   = $this->get('app.api.option');
        $locale          = $request->getLocale();
        $app             = $this->container->getParameter('app');
        $seasonConcern   = $this->get('app.concern.season');
        $websiteConcern  = $this->get('app.concern.website');
        $travelInsurance = $optionService->getTravelInsurancesDescription();

        return $this->render('pages/insurances/' . $locale . '.html.twig', [

            'costs'                     => $seasonService->getInsurancesPolicyCosts(),
            'locale'                    => $locale,
            'damages'                   => true,
            'travel_insurance_possible' => $app['travel_insurance_possible'],
            'ten_days_insurance_price'  => ($seasonConcern->get() === SeasonConcern::SEASON_SUMMER ? $app['ten_days_insurance_price_summer'] : $app['ten_days_insurance_price_default']),
            'travelInsurance'           => $travelInsurance,
            'show_sunnycar'             => $websiteConcern->get() === WebsiteConcern::WEBSITE_ITALISSIMA_NL,
        ]);
    }

    /**
     * @Route("/veel-gestelde-vragen", name="page_faq_nl")
     * @Route("/frequently-asked-questions", name="page_faq_en")
     * @Breadcrumb(name="faq", title="faq", translate=true, active=true)
     */
    public function faq()
    {
        $faqService = $this->get('app.api.faq');
        $items      = $faqService->getItems();

        return $this->render('pages/faq.html.twig', [
            'items' => $items,
        ]);
    }

    /**
     * @Route("/algemenevoorwaarden.php", name="page_terms_nl")
     * @Route("/conditions.php", name="page_terms_en")
     * @Breadcrumb(name="terms", title="terms", translate=true, active=true)
     */
    public function conditions(Request $request)
    {
        return $this->render('pages/conditions/' . $request->getLocale() . '.html.twig', [
            'website' => $this->get('app.concern.website')
        ]);
    }

    /**
     * @Route("/disclaimer.php", name="page_disclaimer_nl")
     * @Route("/disclaimer.php", name="page_disclaimer_en")
     * @Breadcrumb(name="disclaimer", title="disclaimer", translate=true, active=true)
     */
    public function disclaimer(Request $request)
    {
        return $this->render('pages/disclaimer/' . $request->getLocale() . '.html.twig', [
            'website' => $this->get('app.concern.website'),
        ]);
    }

    /**
     * @Route("/privacy-statement.php", name="page_privacy_nl")
     * @Route("/privacy-statement.php", name="page_privacy_en")
     * @Breadcrumb(name="privacy", title="privacy", translate=true, active=true)
     */
    public function privacy(Request $request)
    {
        return $this->render('pages/privacy/' . $request->getLocale() . '.html.twig', [
            'website' => $this->get('app.concern.website'),
        ]);
    }

    /**
     * @Route("/werkenbij", name="page_working", requirements={"_locale": "nl"})
     */
    public function working()
    {
        return $this->render('pages/working.html.twig');
    }

    /**
     * @Route("/zoekopdrachten", name="page_searches_nl")
     * @Route("/searches", name="page_searches_en")
     * @Breadcrumb(name="searches", title="page-searches", translate=true, active=true)
     */
    public function searches()
    {
        return $this->render('pages/searches.html.twig', [
            'saved_searches' => $this->container->get('app.api.user')->user()->getSearches(),
        ]);
    }

    /**
     * @Route("/bekeken-accommodaties", name="page_viewed_nl")
     * @Route("/viewed-accommodations", name="page_viewed_en")
     * @Breadcrumb(name="viewed", title="page-viewed", translate=true, active=true)
     */
    public function viewed()
    {
        $typeIds      = $this->get('app.api.user')->user()->getViewed();
        $typeService  = $this->get('app.api.type');
        $priceService = $this->get('app.api.price');

        $offers       = [];
        $types        = [];

        if (count($typeIds) > 0) {

            $types  = $typeService->findById($typeIds);
            $offers = $priceService->offers($typeIds);
        }

        return $this->render('pages/viewed.html.twig', [

            'types'  => $types,
            'offers' => $offers,
        ]);
    }

    /**
     * @Route("/opgeslagen-accommodatie", name="page_saved_nl")
     * @Route("/saved-accommodation", name="page_saved_en")
     * @Breadcrumb(name="saved", title="page-saved", translate=true, active=true)
     */
    public function saved()
    {
        $typeIds      = $this->get('app.api.user')->user()->getFavorites();
        $typeService  = $this->get('app.api.type');
        $priceService = $this->get('app.api.price');

        $offers       = [];
        $types        = [];

        if (count($typeIds) > 0) {

            $types  = $typeService->findById($typeIds);
            $offers = $priceService->offers($typeIds);
        }

        return $this->render('pages/saved.html.twig', [

            'types'  => $types,
            'offers' => $offers,
        ]);
    }

    /**
     * @Route("/header", name="page_header")
     */
    public function header()
    {
        return $this->render('pages/header.html.twig');
    }

    /**
     * @Route("/zooverawards2015", name="page_zooverawards")
     */
    public function zooverawards()
    {
        return $this->render('pages/zooverawards.html.twig');
    }
}