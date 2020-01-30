<?php

namespace Spicy\SiteBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Psr\Log\LoggerInterface;
use Spicy\SiteBundle\Services\YoutubeAPI;

/**
 * Description of youtubeCommand
 *
 * @author franchesco971
 */
class YoutubeCommand extends ContainerAwareCommand 
{
    private $youtubeAPI;

    private $logger;
    
    public function __construct($name = null, YoutubeAPI $youtubeAPI, LoggerInterface $logger)
    {
        parent::__construct($name);
        $this->logger = $logger;
        $this->youtubeAPI = $youtubeAPI;
    }

    protected function configure()
    {
        $this
            // the name of the command (the part after "app/console")
            ->setName('mimizik:check-youtube')

            // the short description shown while running "php app/console list"
            ->setDescription('Check youtube videos.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Check youtube videos')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $messages = $this->youtubeAPI->getVideos();   
        
        if(is_array($messages)) {
            foreach ($messages as $message) {
                $output->writeln($message);
            }
        } else {
            $this->logger->error("[YoutubeCommand] check-youtube error");
            throw new \Exception("[YoutubeCommand] check-youtube error");
        }            

        $output->writeln('Check finish');
    }
}
