<?php
namespace AppBundle\Command\Github;
use       Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use       Symfony\Component\Console\Input\InputArgument;
use       Symfony\Component\Console\Input\InputInterface;
use       Symfony\Component\Console\Output\OutputInterface;
use       Symfony\Component\Process\Process;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class PullCommand extends ContainerAwareCommand
{
    /**
     * Configuring command
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('github:pull')
             ->setDescription('Pull repository changes from github');
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
        
        if (false === $github->pullAvailable()) {
            return;
        }
        
        $process = new Process('git pull');

        $logger->info('Pulling changes from Github...');
        $process->run();

        if (!$process->isSuccessful()) {

            $logger->error('Could not pull changes from github: ');
            $logger->error($process->getErrorOutput());

        } else {
            $logger->info('Successfully pulled from github');
        }
    }
}