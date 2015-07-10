<?php
namespace AppBundle\Service\Api\GeneralSettings;

/**
 * This is the GeneralSettingsService, with this service you can work with homepage blocks
 *
 * @author  Jeroen Boschman <jeroen@webtastic.nl>
 * @since   0.0.1
 * @package Chalet
 */
class GeneralSettingsService
{
    /**
     * Cache for opened method
     *
     * @var boolean
     */
    private $opened;
    
    /**
     * Opening times
     *
     * @var array
     */
    private $openingTimes = [
        
        'weekday' => [
            
            'open'   => ['hour' => 9,  'minutes' => 0],
            'closed' => ['hour' => 17, 'minutes' => 30],
        ],
        
        'saturday' => [
            
            'open'   => ['hour' => 10, 'minutes' => 0],
            'closed' => ['hour' => 17, 'minutes' => 30],
        ],
    ];
    
    /**
     * @var GeneralSettingsServiceRepositoryInterface
     */
    private $GeneralSettingsServiceRepository;

    /**
     * Constructor
     *
     * @param GeneralSettingsServiceRepositoryInterface $GeneralSettingsServiceRepository
     */
    public function __construct(GeneralSettingsServiceRepositoryInterface $GeneralSettingsServiceRepository)
    {
        $this->generalSettingsServiceRepository = $GeneralSettingsServiceRepository;
    }

    /**
     * Fetch all the homepage blocks
     *
     * Fetching all the homepage blocks based on the options passed in. The supported options are: 'where', 'order', 'limit', 'offset'
     *
     * @param array $options
     * @return GeneralSettingsServiceEntityInterface[]
     */
    public function getNewsletters()
    {
        return $this->generalSettingsServiceRepository->getNewsletters();
    }

    /**
     * Updating and selecting something from the database to see if it responds
     *
     * @return boolean
     */
    public function monitorDatabase()
    {
        return $this->generalSettingsServiceRepository->monitorDatabase();
    }
    
    /**
     * This service endpoints returns a boolean that determines whether company is open or not
     *
     * @return boolean
     */
    public function opened()
    {
        if (null === $this->opened) {
        
            $currentTime = new \DateTime();
            $openedTime  = clone $currentTime;
            $closedTime  = clone $currentTime;
            $weekday     = (int)$currentTime->format('w');
        
            switch (true) {
            
                case ($weekday > 0 && $weekday < 6):
            
                    $times = $this->openingTimes['weekday'];
                    $openedTime->setTime($times['open']['hour'], $times['open']['minutes'], 0);
                    $closedTime->setTime($times['closed']['hour'], $times['closed']['minutes'], 0);
                
                break;
            
                case ($weekday === 6):
            
                    $times = $this->openingTimes['saturday'];
                    $openedTime->setTime($times['open']['hour'], $times['open']['minutes'], 0);
                    $closedTime->setTime($times['closed']['hour'], $times['closed']['minutes'], 0);
                    
                break;
            
                default:
                    $closedTime->setTimestamp($currentTime->getTimestamp() - 1);
            }
        
            $this->opened = ($currentTime->getTimestamp() > $openedTime->getTimestamp()) && ($currentTime->getTimestamp() < $closedTime->getTimestamp());
        }
        
        return $this->opened;
    }
}