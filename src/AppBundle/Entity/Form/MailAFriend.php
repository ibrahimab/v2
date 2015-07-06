<?php
namespace AppBundle\Entity\Form;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class MailAFriend
{
    /**
     * @var string
     */
    private $from_name;
    
    /**
     * @var string
     */
    private $from_email;

    /**
     * @var string
     */
    private $to_email;

    /**
     * @var string
     */
    private $message;

    /**
     * @var array
     */
    private $errors;


    /**
     * Constructor
     *
     * @param array $data
     */
    public function __construct($data)
    {
        $this->errors = [];
        $this->setData($data);
    }

    /**
     * @param array $data
     * @return Contact
     */
    public function setData($data)
    {
        foreach ($data as $field => $value) {

            if (property_exists(get_class($this), $field)) {
                $this->{$field} = $value;
            }
        }

        return $this;
    }

    /**
     * Get data
     * 
     * @return array
     */
    public function getData()
    {
        return [

            'name'        => $this->getName(),
            'email'       => $this->getEmail(),
            'phonenumber' => $this->getPhonenumber(),
            'message'     => $this->getMessage(),
        ];
    }

    /**
     * @return string
     */
    public function getFromName()
    {
        return $this->from_name;
    }

    /**
     * @return string
     */
    public function getFromEmail()
    {
        return $this->from_email;
    }

    /**
     * @return string
     */
    public function getToEmail()
    {
        return $this->to_email;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $field
     * @return Contact
     */
    public function addError($field)
    {
        $this->errors[] = $field;

        return $this;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return void
     */
    public function validate()
    {
        if (empty($this->from_name)) {
            $this->addError('from_name');
        }

        if (empty($this->from_email)) {
            $this->addError('from_email');
        }

        if (empty($this->to_email)) {
            $this->addError('to_email');
        }

        if (empty($this->message)) {
            $this->addError('message');
        }
    }

    /**
     * @return boolean
     */
    public function isValid()
    {
        return (count($this->errors) === 0);
    }
}