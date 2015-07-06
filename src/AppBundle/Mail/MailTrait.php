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
    private $from;

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
    public function setFrom($from)
    {
        $this->from = $from;

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
                ->setFrom($this->from)
                ->setTo($this->to);

        foreach ($this->templates as $multipart => $template) {
            $message->setBody($this->engine->render($template, $data), $multipart);
        }

        return $this->mailer->send($message);
    }
}