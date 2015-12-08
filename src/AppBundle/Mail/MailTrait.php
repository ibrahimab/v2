<?php
namespace AppBundle\Mail;
use       Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
trait MailTrait
{
    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $from_email;

    /**
     * @var string
     */
    private $from_name;

    /**
     * @var string
     */
    private $to;

    /**
     * @var EngineInterface
     */
    private $engine;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var array
     */
    private $templates;


    /**
     * Constructor
     *
     * @param EngineInterface $engine
     * @param \Swift_Mailer $mailer
     */
    public function __construct(EngineInterface $engine, \Swift_Mailer $mailer)
    {
        $this->engine    = $engine;
        $this->mailer    = $mailer;
        $this->templates = [];
    }

    /**
     * @param string $subject
     * @return Contact
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @param string $from
     * @return Contact
     */
    public function setFrom($from_email, $name = '')
    {
        $this->from_email = $from_email;
        $this->from_name  = $from_name;

        return $this;
    }

    /**
     * @param string $to
     * @return Contact
     */
    public function setTo($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @param string $template
     * @param string $multipart
     * @return Contact
     */
    public function setTemplate($template, $multipart)
    {
        $this->templates[$multipart] = $template;

        return $this;
    }

    /**
     * @param array $data
     * @return int
     */
    public function send($data)
    {
        $message = \Swift_Message::newInstance();
        $message->setSubject($this->subject)
                ->setFrom($this->from_email, $this->from_name)
                ->setTo($this->to);

        if (isset($this->templates['text/plain'])) {
            $message->setBody($this->engine->render($this->templates['text/plain'], $data), 'text/plain');
        }

        if (isset($this->templates['text/html'])) {
            $message->addPart($this->engine->render($this->templates['text/html'], $data), 'text/html');
        }

        return $this->mailer->send($message);
    }
}