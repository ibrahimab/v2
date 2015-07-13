<?php
namespace AppBundle\Service\Api\Season;
use       Symfony\Component\HttpFoundation\RequestStack;
use       Symfony\Component\Translation\TranslatorInterface;

class SeasonService
{
    /**
     * @const integer (8 days: 60 * 60 * 24 * 8)
     */
    const WEEKEND_MAX_DIFFERENCE = 691200;
    
    /**
     * @var SeasonServiceRepositoryInterface
     */
    private $seasonServiceRepository;
    
    /**
     * @var RequestStack
     */
    private $requestStack;
    
    /**
     * @var TranslatorInterface
     */
    private $translator;
    
    /**
     * @var string
     */
    private $locale;

    /**
     * Constructor
     *
     * @param SeasonServiceRepositoryInterface $seasonServiceRepository
     */
    public function __construct(SeasonServiceRepositoryInterface $seasonServiceRepository, RequestStack $requestStack, TranslatorInterface $translator)
    {
        $this->seasonServiceRepository = $seasonServiceRepository;
        $this->requestStack            = $requestStack;
        $this->translator              = $translator;
    }
    
    /**
     * @return string
     */
    public function getLocale()
    {
        if (null === $this->locale) {
            
            if (null !== ($request = $this->requestStack->getCurrentRequest())) {
                $this->locale = $request->getLocale();
            }
        }
        
        return $this->locale;
    }

    /**
     * @return float
     */
    public function getInsurancesPolicyCosts()
    {
        return $this->seasonServiceRepository->getInsurancesPolicyCosts();
    }
    
    /**
     * @return array
     */
    public function seasons()
    {
        return $this->seasonServiceRepository->seasons();
    }
    
    /**
     * @return array
     */
    public function weekends($seasons)
    {
        $weekends = [];
        $nextWeek = new \DateInterval('P7D');

        foreach ($seasons as $season) {
            
            $begin = (new \DateTime())->setTimestamp(intval($season['weekend_start']));
            $end   = intval($season['weekend_end']);
            
            while ($begin->getTimestamp() <= $end) {
                
                if ($begin->getTimestamp() >= (time() - self::WEEKEND_MAX_DIFFERENCE)) {
                    $weekends[$begin->getTimestamp()] = $this->translator->trans('weekend') . ' ' . strftime('%e %B %Y', $begin->getTimestamp());
                }
                
                $begin->add($nextWeek);
            }
        }
        
        return $weekends;
    }
}