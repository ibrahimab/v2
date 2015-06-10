<?php
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpFoundation\Request;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(

            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
			new Doctrine\Bundle\MongoDBBundle\DoctrineMongoDBBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Liip\ImagineBundle\LiipImagineBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new AppBundle\AppBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'stag', 'test'))) {

            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new JMS\DiExtraBundle\JMSDiExtraBundle($this);
            $bundles[] = new JMS\AopBundle\JMSAopBundle();
            $bundles[] = new JMS\TranslationBundle\JMSTranslationBundle();
        }

        if ($this->getEnvironment() === 'test') {
            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
        }
        
        $this->registerOldNamespace();

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/' . $this->getEnvironment() . '/config.yml');
        $loader->load(__DIR__.'/config/' . $this->getEnvironment() . '/app.yml');
    }
    
    public function registerOldNamespace()
    {
        $path = __DIR__ . '/../web/chalet';
        
        require_once $path . '/admin/vars_db.php';
        require_once $path . '/admin/class.mysql.php';
        
        spl_autoload_register(function($class) {

            if (file_exists($path = __DIR__ . '/../src/old-classes/siteclass.' . $class . '.php')) {
                require_once $path;
            }
        });
    }
    
    public function initializeContainer()
    {
        parent::initializeContainer();
        
        if (PHP_SAPI === 'cli') {
            
            // cli is being used, lets create a mock request
            // @TODO: remove this when symfony2 fixes this problem
            // when using cli commands, all classes that use the request service 
            // creates this error:  You cannot create a service ("request") of an inactive scope ("request").
            // DATE: 2015-06-10 11:44
            $request = new Request();
            $request->create('/');
            
            $this->getContainer()->enterScope('request');
            $this->getContainer()->set('request', $request, 'request');
        }
    }
}
