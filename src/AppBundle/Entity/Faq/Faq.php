<?php
namespace AppBundle\Entity\Faq;
use       AppBundle\Service\Api\Faq\FaqServiceEntityInterface;
use       Doctrine\ORM\Mapping as ORM;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.1
 */
/**
 * FAQ
 *
 * @ORM\Table(name="faq")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Faq\FaqRepository")
 */
class Faq implements FaqServiceEntityInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="faq_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var array
     *
     * @ORM\Column(name="websites", type="simple_array")
     */
    private $websites;

    /**
     * @var array
     *
     * @ORM\Column(name="volgorde", type="smallint")
     */
    private $order;

    /**
     * @var string
     *
     * @ORM\Column(name="vraag", type="string", length=100)
     */
    private $question;

    /**
     * @var string
     *
     * @ORM\Column(name="vraag_en", type="string", length=100)
     */
    private $englishQuestion;

    /**
     * @var string
     *
     * @ORM\Column(name="vraag_de", type="string", length=100)
     */
    private $germanQuestion;

    /**
     * @var string
     *
     * @ORM\Column(name="antwoord", type="string", length=100)
     */
    private $answer;

    /**
     * @var string
     *
     * @ORM\Column(name="antwoord_en", type="string", length=100)
     */
    private $englishAnswer;

    /**
     * @var string
     *
     * @ORM\Column(name="antwoord_de", type="string", length=100)
     */
    private $germanAnswer;


    /**
     * {@InheritDoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@InheritDoc}
     */
    public function setWebsites($websites)
    {
        $this->websites = $websites;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getWebsites()
    {
        return $this->websites;
    }

    /**
     * {@InheritDoc}
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * {@InheritDoc}
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * {@InheritDoc}
     */
    public function setEnglishQuestion($englishQuestion)
    {
        $this->englishQuestion = $englishQuestion;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getEnglishQuestion()
    {
        return $this->englishQuestion;
    }

    /**
     * {@InheritDoc}
     */
    public function setGermanQuestion($germanQuestion)
    {
        $this->germanQuestion = $germanQuestion;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getGermanQuestion()
    {
        return $this->germanQuestion;
    }

    /**
     * {@InheritDoc}
     */
    public function setLocaleQuestions($localeQuestions)
    {
        // normalize locales
        $localeQuestions = array_change_key_case($localeQuestions);

        $this->setQuestion(isset($localeQuestions['nl']) ? $localeQuestions['nl'] : '');
        $this->setEnglishQuestion(isset($localeQuestions['en']) ? $localeQuestions['en'] : '');
        $this->setGermanQuestion(isset($localeQuestions['de']) ? $localeQuestions['de'] : '');

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getLocaleQuestion($locale)
    {
        return $this->getLocaleField('question', $locale, ['nl', 'en', 'de']);
    }

    /**
     * {@InheritDoc}
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * {@InheritDoc}
     */
    public function setEnglishAnswer($englishAnswer)
    {
        $this->englishAnswer = $englishAnswer;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getEnglishAnswer()
    {
        return $this->englishAnswer;
    }

    /**
     * {@InheritDoc}
     */
    public function setGermanAnswer($germanAnswer)
    {
        $this->germanAnswer = $germanAnswer;

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getGermanAnswer()
    {
        return $this->germanAnswer;
    }

    /**
     * {@InheritDoc}
     */
    public function setLocaleAnswers($localeAnswers)
    {
        // normalize locales
        $localeAnswers = array_change_key_case($localeAnswers);

        $this->setAnswer(isset($localeAnswers['nl']) ? $localeAnswers['nl'] : '');
        $this->setEnglishAnswer(isset($localeAnswers['en']) ? $localeAnswers['en'] : '');
        $this->setGermanAnswer(isset($localeAnswers['de']) ? $localeAnswers['de'] : '');

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function getLocaleAnswer($locale)
    {
        return $this->getLocaleField('answer', $locale, ['nl', 'en', 'de']);
    }

    /**
     * {@InheritDoc}
     */
    public function getLocaleField($field, $locale, $allowedLocales)
    {
        $locale        = strtolower($locale);
        $allowedLocale = in_array($locale, $allowedLocales);

        switch (true) {

            case $allowedLocale && $locale === 'en':
                $localized = $this->{'getEnglish' . $field}();
                break;

            case $allowedLocale && $locale === 'de':
                $localized = $this->{'getGerman' . $field}();
                break;

            case $allowedLocale && $locale === 'nl':
            default:
                $localized = $this->{'get' . $field}();
                break;
        }

        return $localized;
    }
}