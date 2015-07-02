<?php
namespace AppBundle\Controller;
use       AppBundle\Annotation\Breadcrumb;
use       AppBundle\Entity\Form\Contact;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;
use       Symfony\Component\HttpFoundation\Request;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 *
 * @Breadcrumb(name="frontpage", title="frontpage", translate=true, path="home")
 * @Breadcrumb(name="contact", title="contact", translate=true, path="contact")
 */
class ContactController extends Controller
{
    /**
     * @Route("/contact.php", name="contact")
     * @Method("GET")
     */
    public function newAction(Request $request)
    {
        return $this->render('contact/new.html.twig', [

            'website_concern' => $this->get('app.concern.website'),
            'form'            => [

                'errors'  => [],
                'contact' => [

                    'name'        => '',
                    'email'       => '',
                    'phonenumber' => '',
                    'message'     => '',
                ],
            ],
        ]);
    }

    /**
     * @Route("/contact.php", name="create_contact")
     * @Method("POST")
     */
    public function create(Request $request)
    {
        $contact = new Contact($request->get('contact'));
        $contact->validate();

        if ($contact->isValid()) {

            $mailer = $this->get('app.mailer.contact');
            $result = $mailer->setSubject($this->get('translator')->trans('form-contact-subject'))
                             ->setFrom($this->container->getParameter('mailer_from'))
                             ->setTo($this->container->getParameter('mailer_to'))
                             ->setTemplate('mail/contact.html.twig', 'text/html')
                             ->setTemplate('mail/contact.txt.twig', 'text/plain')
                             ->send($contact->getData());

            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/new.html.twig', [

            'website_concern' => $this->get('app.concern.website'),
            'form'            => [

                'errors'  => $contact->getErrors(),
                'contact' => [

                    'name'        => $contact->getName(),
                    'email'       => $contact->getEmail(),
                    'phonenumber' => $contact->getPhonenumber(),
                    'message'     => $contact->getMessage(),
                ],
            ],
        ]);
    }
}