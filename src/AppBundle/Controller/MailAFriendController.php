<?php
namespace AppBundle\Controller;
use       AppBundle\Annotation\Breadcrumb;
use       AppBundle\Entity\Form\MailAFriend;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;
use       Symfony\Component\HttpFoundation\Request;
use       Doctrine\ORM\NoResultException;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 *
 * @Breadcrumb(name="frontpage", title="frontpage", translate=true, path="home")
 * @Breadcrumb(name="mail_a_friend", title="mail-a-friend", translate=true, active=true)
 */
class MailAFriendController extends Controller
{
    /**
     * @Route("/mail-een-vriend/{beginCode}{typeId}", name="mail_a_friend_nl", requirements={
     *    "beginCode": "[A-Z]{1,2}",
     *    "typeId": "\d+"
     * })
     * @Route("/mail-a-friend/{beginCode}{typeId}", name="mail_a_friend_en", requirements={
     *    "beginCode": "[A-Z]{1,2}",
     *    "typeId": "\d+"
     * })
     * @Method("GET")
     */
    public function newAction($beginCode, $typeId)
    {
        try {
            
            $typeService = $this->get('app.api.type');
            $type        = $typeService->findById($typeId);
            
        } catch (NoResultException $exception) {
            throw $this->createNotFoundException(sprintf('Type with ID=%d could not be found', $typeId));
        }
        
        $pricesService = $this->get('old.prices.wrapper');
        $prices        = $pricesService->get($typeIds);

        $priceService  = $this->get('app.api.price');
        $offers        = $priceService->offers($typeIds);
        
        return $this->render('mail-a-friend/new.html.twig', [

            'beginCode' => $beginCode,
            'typeId'    => $typeId,
            'type'      => $type,
            'prices'    => $prices,
            'offers'    => $offers,
            'form'      => [

                'errors'  => [],
                'mail_a_friend' => [

                    'from_name'  => '',
                    'from_email' => '',
                    'to_email'   => '',
                    'message'    => '',
                ],
            ],
        ]);
    }
    
    /**
     * @Route("/mail-a-friend/{beginCode}{typeId}", name="process_mail_a_friend", requirements={
     *    "beginCode": "[A-Z]{1,2}",
     *    "typeId": "\d+"
     * })
     * @Method("POST")
     */
    public function create(Request $request)
    {
        
    }
}