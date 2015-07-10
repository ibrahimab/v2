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
                    'message'    => $this->get('translator')->trans('form-mail-a-friend-message-placeholder', ['%website_name%' => $this->get('app.concern.website')->name()]),
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
    public function create($beginCode, $typeId, Request $request)
    {
        try {
            
            $typeService = $this->get('app.api.type');
            $type        = $typeService->findById($typeId);
            
        } catch (NoResultException $exception) {
            throw $this->createNotFoundException(sprintf('Type with ID=%d could not be found', $typeId));
        }
        
        $mailAFriend = new MailAFriend($request->get('mail_a_friend'));
        $mailAFriend->validate();

        if ($mailAFriend->isValid()) {

            $data         = $mailAFriend->getData();
            $data['type'] = $type;
            $locale       = $request->getLocale();
            $to           = $mailAFriend->getToEmail();
            $mailer       = $this->get('app.mailer.mail.a.friend');
            $result       = $mailer->setSubject($type->getAccommodation()->getLocaleName($locale) . ' ' . $type->getLocaleName($locale))
                                   ->setFrom($mailAFriend->getFromEmail(), $mailAFriend->getFromName())
                                   ->setTo(explode(',', $to))
                                   ->setTemplate('mail/mail-a-friend.html.twig', 'text/html')
                                   ->setTemplate('mail/mail-a-friend.txt.twig', 'text/plain')
                                   ->send($data);

            return $this->redirectToRoute('mail_a_friend_' . $request->getLocale(), ['beginCode' => $beginCode, 'typeId' => $typeId]);
        }

        $priceService  = $this->get('app.api.price');
        $offers        = $priceService->offers($typeIds);

        return $this->render('mail-a-friend/new.html.twig', [

            'beginCode' => $beginCode,
            'typeId'    => $typeId,
            'type'      => $type,
            'prices'    => $prices,
            'offers'    => $offers,
            'form'      => [

                'errors'  => $mailAFriend->getErrors(),
                'mail_a_friend' => [

                    'from_name'  => $mailAFriend->getFromName(),
                    'from_email' => $mailAFriend->getFromEmail(),
                    'to_email'   => $mailAFriend->getToEmail(),
                    'message'    => $mailAFriend->getMessage(),
                ],
            ],
        ]);
    }
}