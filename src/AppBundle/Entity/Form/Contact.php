<?php
namespace AppBundle\Entity\Form;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class Contact
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $phonenumber;

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPhonenumber()
    {
        return $this->phonenumber;
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
        if (empty($this->name)) {
            $this->addError('name');
        }

        if (empty($this->email) && empty($this->phonenumber)) {

            $this->addError('email');
            $this->addError('phonenumber');
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