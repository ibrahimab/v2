<?php
namespace AppBundle\Command;
use       Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use       Symfony\Component\Console\Input\InputInterface;
use       Symfony\Component\Console\Output\OutputInterface;
use       Symfony\Component\Process\Process;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class DeployCommand extends ContainerAwareCommand
{
    /**
     * Configuring command
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('deploy:app')
             ->setDescription('Deploying application');
    }

    /**
     * Execute command
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $logger    = $container->get('monolog.logger.application');
        $github    = $container->get('app.github');
        
        
        if (true === $github->isPulling()) {
            
            $logger->error(sprintf('Deployment has already been started at: %s', $github->getStartPulling()->format('d-m-Y H:i:s'));
            return;
        }
        
        if (false === $github->pullAvailable()) {
            return;
        }
        
        $process = new Process(sprintf('sh %s/../deploy.sh', $container->getParameter('kernel.root_dir')));

        $github->startPulling();
        $logger->info('Deploying application...');
        $process->run();

        if (!$process->isSuccessful()) {

            $logger->error('Could not deploy application');
            $logger->error($process->getErrorOutput());

        } else {
            
            $pushTime = $github->pullCompleted();
            $logger->info(sprintf('Successfully deployed application that was marked at: %s', $pushTime->format('d-m-Y H:i:s')));
        }
    }
}