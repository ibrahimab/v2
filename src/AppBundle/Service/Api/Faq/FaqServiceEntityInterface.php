<?php
namespace AppBundle\Service\Api\Faq;
use       AppBundle\Service\Api\Faq\FaqServiceEntityInterface;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
interface FaqServiceEntityInterface
{
    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set websites
     *
     * @param  array $websites
     * @return TypeServiceEntityInterface
     */
    public function setWebsites($websites);

    /**
     * Get websites
     *
     * @return array
     */
    public function getWebsites();

    /**
     * Setting order
     *
     * @param integer $order
     * @return FaqServiceEntityInterface
     */
    public function setOrder($order);

    /**
     * Get order
     *
     * @return integer
     */
    public function getOrder();

    /**
     * Set Question
     *
     * @param string $question
     * @return FaqServiceEntityInterface
     */
    public function setQuestion($question);

    /**
     * Get question
     *
     * @return string
     */
    public function getQuestion();

    /**
     * Set English Question
     *
     * @param string $englishQuestion
     * @return FaqServiceEntityInterface
     */
    public function setEnglishQuestion($englishQuestion);

    /**
     * Get english question
     *
     * @return string
     */
    public function getEnglishQuestion();

    /**
     * Set German Question
     *
     * @param string $germanQuestion
     * @return FaqServiceEntityInterface
     */
    public function setGermanQuestion($germanQuestion);

    /**
     * Get german question
     *
     * @return string
     */
    public function getGermanQuestion();

    /**
     * Set Locale Questions
     *
     * @param array $localeQuestions
     * @return FaqServiceEntityInterface
     */
    public function setLocaleQuestions($localeQuestions);

    /**
     * Get locale question
     *
     * @return string
     */
    public function getLocaleQuestion($locale);

    /**
     * Set Answer
     *
     * @param string $answer
     * @return FaqServiceEntityInterface
     */
    public function setAnswer($answer);

    /**
     * Get answer
     *
     * @return string
     */
    public function getAnswer();

    /**
     * Set English Answer
     *
     * @param string $englishAnswer
     * @return FaqServiceEntityInterface
     */
    public function setEnglishAnswer($englishAnswer);

    /**
     * Get english answer
     *
     * @return string
     */
    public function getEnglishAnswer();

    /**
     * Set German Answer
     *
     * @param string $germanAnswer
     * @return FaqServiceEntityInterface
     */
    public function setGermanAnswer($germanAnswer);

    /**
     * Get german answer
     *
     * @return string
     */
    public function getGermanAnswer();

    /**
     * Set Locale Answers
     *
     * @param string $localeAnswers
     * @return FaqServiceEntityInterface
     */
    public function setLocaleAnswers($localeAnswers);

    /**
     * Get locale answer
     *
     * @return string
     */
    public function getLocaleAnswer($locale);

    /**
     * General locale field getter
     *
     * @param string $field
     * @param string $locale
     * @param array $allowedLocales
     * @return string
     */
    public function getLocaleField($field, $locale, $allowedLocales);
}