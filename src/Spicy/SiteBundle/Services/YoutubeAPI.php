<?php

namespace Spicy\SiteBundle\Services;

use Spicy\SiteBundle\Entity\Video;
use Spicy\AppBundle\Entity\Channel;
use Doctrine\ORM\EntityManagerInterface;
use Spicy\SiteBundle\Services\ApprovalService;
use Psr\Log\LoggerInterface;

/**
 *  cd /home/mimizikc/public_html && /usr/local/bin/php /home/mimizikc/public_html/app/console mimizik:check-youtube
 */
class YoutubeAPI
{
    private $developerKey;    
    private $logger;
    private $em;
    private $baseURL;
    private $approvalService;
    private $errorMessages;
    private $nbNewChannels;
    private $nbNewApprovals;

    public function __construct($developerKey, LoggerInterface $logger, EntityManagerInterface $entityManager, ApprovalService $approvalService)
    {
        $this->developerKey = $developerKey;
        $this->logger = $logger;
        $this->em = $entityManager;
        $this->baseURL = "https://www.googleapis.com/youtube/v3/";
        $this->approvalService = $approvalService;
        $this->errorMessages = [];
        $this->nbNewChannels = $this->nbNewApprovals = 0;
    }

    /**
     * getJSONResponse
     *
     * @param integer $videoId
     * @return array
     * @throws \Exception
     */
    public function getJSONResponse($videoId)
    {
        $url = $this->baseURL . "videos?id=$videoId&key=" . $this->developerKey . "&part=snippet%2CtopicDetails%2CcontentDetails";

        return $this->getJSONObject($url);
    }

    /**
     * getJSONObject
     *
     * @param string $url
     * @return array
     * @throws \Exception
     */
    public function getJSONObject($url)
    {
        $obj = null;

        try {
            $json = file_get_contents($url);
            if ($json == false) {
                throw new \Exception("Pas de données");
            }

            $obj = json_decode($json, true);
        } catch (\Exception $ex) {
            $this->logger->error("L'api ne reponds pas : " . $ex->getMessage(), ['url' => $url]);
            throw new \Exception("L'api ne reponds pas : $url -> " . $ex->getMessage()); 
        }

        return $obj;
    }
    
    /**
     * 
     * @param string $yurl
     * @return array
     * @throws \Exception
     */
    public function getArrayResult($yurl)
    {
        $arrayResult = [];
        $objJSON = $this->getJSONResponse($yurl);

        if (empty($objJSON)) {
            return $arrayResult;
        }

        $snippet = $objJSON['items'][0]['snippet'];
        $arrayResult['id'] = $objJSON['items'][0]['id'];
        $arrayResult['title'] = $snippet['title'];
        $arrayResult['description'] = $snippet['description'];
        $arrayResult['channelTitle'] = $snippet['channelTitle'];        

        return $arrayResult;
    }
    
    /**
     * 
     * @param array $arrayResult
     * @return Video
     */
    private function setVideoData($arrayResult) 
    {
        $video = new Video;
        $video->setUrl($arrayResult['id']);
        $video->SetDescription($arrayResult['description']);
        $video->setTitre($arrayResult['title']);
        $video->setSource($arrayResult['channelTitle']);
                
        return $video;
    }
    
    /**
     * 
     * @param string $yurl
     * @return Video|null
     * @throws \Exception
     */
    public function getByYoutubeId($yurl)
    {        
        $arrayResult = $this->getArrayResult($yurl);
        $video = $this->setVideoData($arrayResult);
        
        return $video;
    }
    
    /**
     * getVideos
     *
     * @return array
     * @throws Exception
     */
    public function getVideos() 
    {        
        $nextPageToken = $nbPage = null;
        $page = 0;
        $this->logger->info('[YOUTUBEAPI] : getVideos');
        
        do {
            $subscriptionsURL = $this->baseURL . "subscriptions?part=snippet%2CcontentDetails&channelId=UCygqmomnv_HLr-Df0LlxsiQ&key=" . $this->developerKey;
            
            if ($nextPageToken) {
                $subscriptionsURL = $subscriptionsURL . '&pageToken=' . $nextPageToken;
            }
            
            try {
                $subscriptions = $this->getJSONObject($subscriptionsURL);
            } catch (\Exception $ex) {
                $this->logger->error('[YOUTUBEAPI] getJSONObject : '.$ex->getMessage());
                throw new \Exception("[YOUTUBEAPI] getJSONObject : ".$ex->getMessage());
            }
            
            if (!empty($subscriptions)) {                
                $nextPageToken = $this->getNextPageToken($subscriptions, $nextPageToken);

                $nbPage = $this->getNbPage($subscriptions, $nbPage);
                
                try{
                    $this->fetchSubscriptions($subscriptions);
                } catch (\Exception $ex) {
                    $this->logger->error('[YOUTUBEAPI] getVideos : '.$ex->getMessage());
                    throw new \Exception("[YOUTUBEAPI] getVideos : ".$ex->getMessage());
                }                
            }
            
            $page++;
        } while ($page < $nbPage);
        
        $this->em->flush();
        
        $this->errorMessages[] = " ".$this->nbNewChannels." Channels";
        $this->errorMessages[] = " ".$this->nbNewApprovals." Approvals";
        
        return $this->errorMessages;
    }

    /**
     * fetchSubscriptions
     *
     * @param array $subscriptions
     * @return void
     */
    private function fetchSubscriptions($subscriptions = null)
    {
        $items = $subscriptions['items'];
        $nbchannel = 0;
        $maxChannels = 60;
        
        if (!is_array($items)) {
            $this->logger->error('[fetchSubscriptions] : No items');
            throw new \Exception('[fetchSubscriptions] : No items');
        }

        foreach ($items as $channelItem) {
            $channelId = $channelItem['snippet']['resourceId']['channelId'];
            $totalItemCount = $channelItem['contentDetails']['totalItemCount'];
            $channel = $this->em->getRepository(Channel::class)->findOneBy(['channelId' => $channelId]);

            if (!$channel) {
                $channel = new Channel();
                $channel->setTitle($channelItem['snippet']['title'])
                    ->setChannelId($channelId);

                $this->nbNewChannels++;
            }
            
            if (
                $channel->getTotalItemCount() != $totalItemCount && 
                $nbchannel < $maxChannels
            ) { // si le total a changé
                $channel->setTotalItemCount($totalItemCount);

                $searchURL = $this->baseURL . "search?part=snippet&channelId=" . $channelId .
                    "&maxResults=10&order=date&publishedAfter=2018-11-01T00%3A00%3A00Z&key=" . $this->developerKey;

                $videos = $this->getJSONObject($searchURL);

                try {
                    $this->searchVideos($videos);
                } catch (\Exception $ex) {
                    $this->logger->error('[fetchSubscriptions] : ' . $ex->getMessage());
                    throw new \Exception('[fetchSubscriptions] : ' . $ex->getMessage());
                }

                $nbchannel++;
            }

            $this->em->persist($channel);
        }
        
    }
    
    /**
     * searchVideos
     *
     * @param array $videos
     * @return void
     * @throws \Exception
     */
    private function searchVideos($videos) 
    {              
        if (!is_array($videos['items'])) {
            throw new \Exception("searchVideos No items error");
        }

        foreach ($videos['items'] as $videoItem) {
            if ($videoItem['id']['kind'] == "youtube#video") {
                $videoId = $videoItem['id']['videoId'];
                
                $this->getVideoData($videoId);                    
            }
        }                 
    }

    /**
     * getVideoData
     *
     * @param string|integer $videoId
     * @return void
     */
    private function getVideoData($videoId)
    {
        $videoJson = $this->getJSONResponse($videoId);

        if ($videoJson) {
            $duration = $this->covtime($videoJson['items'][0]['contentDetails']['duration']);

            if ($duration > 120 && $duration < 600) { //entre 2 et 10 minutes

                $this->createApproval($videoJson);

                $this->em->flush();
            }
        }
    }

    /**
     * createApproval
     *
     * @param array $videoJson
     * @return void
     */
    private function createApproval($videoJson)
    {
        $url = $videoJson['items'][0]['id'];
        $video = $this->em->getRepository(Video::class)->findOneBy(['url' => $url]);

        if ($video) {
            $this->logger->info('[createApproval] Video already exist', ['url' => $url]);
            return;
        }

        $snippet = $videoJson['items'][0]['snippet'];

        $video = (new Video())->setUrl($url)
            ->setDescription(htmlspecialchars(utf8_decode($snippet['description'])))
            ->setTitre(htmlspecialchars(utf8_decode($snippet['title'])))
            ->setSource($snippet['channelTitle']);

        $this->em->persist($video);

        $approval = $this->approvalService->getDefaultApproval($video);

        $this->em->persist($approval);

        $this->nbNewApprovals++;
        
    }

    private function getNextPageToken($subscriptions, $nextPageToken = null)
    {
        if (isset($subscriptions['nextPageToken'])) {
            $nextPageToken = $subscriptions['nextPageToken'];
        }

        return $nextPageToken;
    }

    private function getNbPage($subscriptions, $nbPage = null)
    {
        if (!$nbPage) {
            $totalResults = $subscriptions['pageInfo']['totalResults'];
            $this->errorMessages[] = " $totalResults abonnements";
            $resultsPerPage = $subscriptions['pageInfo']['resultsPerPage'];
            $nbPage = ceil($totalResults / $resultsPerPage);
        }

        return $nbPage;
    }

    public function covtime($youtube_time)
    {
        $start = new \DateTime('@0'); // Unix epoch
        $start->add(new \DateInterval($youtube_time));
        return $start->getTimestamp();
    }   
}

