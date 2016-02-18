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
     * Constructor
     *
     * @param SeasonServiceRepositoryInterface $seasonServiceRepository
     */
    public function __construct(SeasonServiceRepositoryInterface $seasonServiceRepository)
    {
        $this->seasonServiceRepository = $seasonServiceRepository;
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
    public function current()
    {
        $seasons = $this->seasons();
        $season  = current($seasons);

        return $season;
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
                    $weekends[$begin->getTimestamp()] = strftime('%e %B %Y', $begin->getTimestamp());
                }

                $begin->add($nextWeek);
            }
        }

        return $weekends;
    }
}