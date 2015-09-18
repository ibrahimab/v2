<?php
namespace AppBundle\Entity\Form;
use       Symfony\Component\Validator\Validator\RecursiveValidator;
use       Symfony\Component\Validator\Constraints as Assert;

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
     * @var RecursiveValidator
     */
    private $validator;


    /**
     * Constructor
     *
     * @param array $data
     */
    public function __construct($data, RecursiveValidator $validator)
    {
        $this->errors    = [];
        $this->validator = $validator;
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

            'from_name'   => $this->getFromName(),
            'from_email'  => $this->getFromEmail(),
            'to_email'    => $this->getToEmail(),
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
    public function addError($field, $identifier)
    {
        $this->errors[$field] = $identifier;

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
            $this->addError('from_name', 'empty');
        }

        if (empty($this->from_email)) {
            $this->addError('from_email', 'empty');
        }

        if (empty($this->to_email)) {
            $this->addError('to_email', 'empty');
        }

        if (empty($this->message)) {
            $this->addError('message', 'empte');
        }
        
        if (!empty($this->to_email)) {
            
            $addresses  = explode(',', $this->to_email);
            $error      = false;
            $constraint = new Assert\Email();
            
            foreach ($addresses as $address) {
                
                if (empty($address)) {
                    
                    $error = true;
                    continue;
                }
                
                $errorList = $this->validator->validate($address, $constraint);
                
                if (count($errorList) > 0) {
                    $error = true;
                }
            }
            
            if (true === $error) {
                $this->addError('to_email', 'invalid');
            }
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