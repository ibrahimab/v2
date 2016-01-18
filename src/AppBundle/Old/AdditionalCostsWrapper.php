<?php
namespace AppBundle\Old;

use       \bijkomendekosten;
use       Chalet\Translator\Translator;

class AdditionalCostsWrapper
{
    public function __construct($database, $redis, $websiteConcern)
    {
        $this->database       = $database;
        $this->redis          = $redis;
        $this->websiteConcern = $websiteConcern;
    }

    public function getTranslator()
    {
        if (null === $this->translator) {

            $this->translator = new Translator();
            $this->translator->addMessages();
            $this->translator->addMessages();
            $this->translator->setPath('/');
            $this->translator->setFullPath('/');
            $this->translator->setWebsite([

                'language' => $this->websiteConcern
            ]);
        }

        return $this->translator;
    }

    public function initialize()
    {
        $this->costs = new bijkomendekosten;
        $this->costs->setDatabase($this->database);
        $this->costs->setRedis($this->redis);
        $this->costs->setTranslator($this->getTranslator());
    }
}