<?php

namespace Spicy\SiteBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Monolog\Logger;

/**
 * Description of youtubeCommand
 *
 * @author franchesco971
 */
class YoutubeCommand extends ContainerAwareCommand {
    
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
        $youtubeAPI = $this->getContainer()->get('mimizik.youtube.api');

        $messages = $youtubeAPI->getVideos();   
        
        if(is_array($messages)) {
            foreach ($messages as $message) {
                $output->writeln($message);
            }
        } else {
            throw new Exception("check-youtube error");
        }
            

        $output->writeln('Check finish');
    }
}
