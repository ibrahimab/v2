<?php
namespace AppBundle\Service\Api\Faq;

/**
 * This is the FaqService, with this service you can manipulate FAQ items
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @version 0.0.5
 * @since   0.0.5
 * @package Chalet
 */
class FaqService
{
    /**
     * @var FaqServiceRepositoryInterface
     */
    private $faqServiceRepository;

    /**
     * Constructor
     *
     * @param FaqServiceRepositoryInterface $faqServiceRepository
     */
    public function __construct(FaqServiceRepositoryInterface $faqServiceRepository)
    {
        $this->faqServiceRepository = $faqServiceRepository;
    }

    public function getItems()
    {
        return $this->faqServiceRepository->getItems();
    }
}