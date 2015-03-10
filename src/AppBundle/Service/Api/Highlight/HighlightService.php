<?php
namespace AppBundle\Service\Api\Highlight;

/**
 * This is the highlight service
 */
class HighlightService
{
    /**
     * @var HighlightServiceRepositoryInterface
     */
    private $highlightServiceRepository;
    
    /**
     * Constructor
     *
     * @param HighlightServiceRepositoryInterface $highlightServiceRepository
     */
    public function __construct(HighlightServiceRepositoryInterface $highlightServiceRepository)
    {
        $this->highlightServiceRepository = $highlightServiceRepository;
    }
    
    /**
     * Getting all the highlights
     *
     * The options parameters supports the following: 'where', 'order', 'limit'
     *
     * @param  array $options
     * @return HighlightServiceEntityInterface[]
     */
    public function all($options = [])
    {
        return $this->highlightServiceRepository->all($options);
    }
    
    /**
     * Getting one highlight by some criteria
     *
     * @param array $by
     * @param HighlightServiceEntityInterface|null
     */
    public function find($by)
    {
        return $this->highlightServiceRepository->find($by);
    }
    
    /**
     * This method fetches all displayable highlights from the repository
     * This means the 'display' flag has to be set and the current date has to fall between published and expired date
     *
     * @param  array     $options
     * @param  \DateTime $datetime
     * @return HighlightServiceEntityInterface[]
     */
    public function displayable($options = [], $datetime = null)
    {   
        return $this->highlightServiceRepository->displayable($options, $datetime);
    }
}