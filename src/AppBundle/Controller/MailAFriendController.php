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
     * @Route("/mail-een-vriend/{countryCode}{typeId}", name="mail_a_friend_nl", requirements={
     *    "countryCode": "[A-Z]{1,2}",
     *    "typeId": "\d+"
     * })
     * @Route("/mail-a-friend/{countryCode}{typeId}", name="mail_a_friend_en", requirements={
     *    "countryCode": "[A-Z]{1,2}",
     *    "typeId": "\d+"
     * })
     * @Method("GET")
     */
    public function newAction($countryCode, $typeId, Request $request)
    {
        try {

            $typeService = $this->get('app.api.type');
            $type        = $typeService->getTypeById($typeId);

        } catch (NoResultException $exception) {
            throw $this->createNotFoundException(sprintf('Type with ID=%d could not be found', $typeId));
        }

        $pricesAndOffersService = $this->get('app.api.prices_and_offers');
        $params                 = $pricesAndOffersService->createParamsFromRequest($request);
        $offers                 = $pricesAndOffersService->getOffers([$typeId]);
        $prices                 = $pricesAndOffersService->getPrices([$typeId], $params);

        $surveyService          = $this->get('app.api.booking.survey');
        $surveys                = $surveyService->normalize($surveyService->statsByType($type['type_id']));

        return $this->render('mail-a-friend/new.html.twig', [

            'countryCode' => $countryCode,
            'typeId'    => $typeId,
            'type'      => $type,
            'place'     => $type['place_name'],
            'prices'    => $prices,
            'offers'    => $offers,
            'surveys'   => $surveys,
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
     * @Route("/mail-a-friend/{countryCode}{typeId}", name="process_mail_a_friend", requirements={
     *    "countryCode": "[A-Z]{1,2}",
     *    "typeId": "\d+"
     * })
     * @Method("POST")
     */
    public function create($countryCode, $typeId, Request $request)
    {
        try {

            $typeService  = $this->get('app.api.type');
            $priceService = $this->get('app.api.price');

            $type         = $typeService->findById($typeId);
            $offers       = $priceService->offers([$typeId]);

        } catch (NoResultException $exception) {
            throw $this->createNotFoundException(sprintf('Type with ID=%d could not be found', $typeId));
        }

        $mailAFriend = new MailAFriend($request->get('mail_a_friend'), $this->get('validator'));
        $mailAFriend->validate();

        if ($mailAFriend->isValid()) {

            $data           = $mailAFriend->getData();
            $data['type']   = $type;
            $data['offers'] = $offers;
            $locale         = $request->getLocale();
            $to             = $mailAFriend->getToEmail();
            $mailer         = $this->get('app.mailer.mail.a.friend');
            $result         = $mailer->setSubject($type->getAccommodation()->getLocaleName($locale) . ' ' . $type->getLocaleName($locale))
                                     ->setFrom($mailAFriend->getFromEmail(), $mailAFriend->getFromName())
                                     ->setTo(explode(',', $to))
                                     ->setTemplate('mail/mail-a-friend.html.twig', 'text/html')
                                     // ->setTemplate('mail/mail-a-friend.txt.twig', 'text/plain')
                                     ->send($data);

            // return $this->redirectToRoute('mail_a_friend_' . $request->getLocale(), ['countryCode' => $countryCode, 'typeId' => $typeId]);
        }

        $priceService  = $this->get('app.api.price');
        $offers        = $priceService->offers([$typeId]);

        return $this->render('mail-a-friend/new.html.twig', [

            'countryCode' => $countryCode,
            'typeId'    => $typeId,
            'type'      => $type,
            'place'     => $type['place_name'],
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
