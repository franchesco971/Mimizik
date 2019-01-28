<?php

namespace Spicy\SiteBundle\Services;

use Spicy\SiteBundle\Entity\Video;
use Spicy\AppBundle\Entity\Channel;
use Spicy\SiteBundle\Entity\Approval;
use Doctrine\ORM\EntityManager;
use Spicy\SiteBundle\Services\ApprovalService;

/**
 * 
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

    public function __construct($logger, $developerKey, EntityManager $entityManager, ApprovalService $approvalService)
    {
        $this->developerKey = $developerKey;
        $this->logger = $logger;
        $this->em = $entityManager;
        $this->baseURL = "https://www.googleapis.com/youtube/v3/";
        $this->approvalService = $approvalService;
        $this->errorMessages = [];
        $this->nbNewChannels = $this->nbNewApprovals = 0;
    }
    
    public function getJSONResponse($videoId) 
    {
        $url = $this->baseURL . "videos?id=$videoId&key=".$this->developerKey."&part=snippet,topicDetails%2CcontentDetails";
        
        
        return $this->getJSONObject($url);
    }
    
    public function getJSONObject($url) {
        $obj = null;
        
        try {
            $json = file_get_contents($url);
            if($json == false) {
                throw new Exception("Pas de données");                
            }
            
            $obj = json_decode($json);
            
        } catch (Exception $ex) {
            $this->logger->error($ex->getMessage());
            throw new Exception("L'api ne reponds pas");
        }
        
        return $obj;
    }
    
    /**
     * 
     * @param string $yurl
     * @return array
     */
    public function getArrayResult($yurl) 
    {
        $arrayResult = array();
        $objJSON = $this->getJSONResponse($yurl);
        
        if($objJSON) {
            $snippet = $objJSON->items[0]->snippet;
            $arrayResult['id'] = $objJSON->items[0]->id;
            $arrayResult['title'] = $snippet->title;
            $arrayResult['description'] = $snippet->description;
            $arrayResult['channelTitle'] = $snippet->channelTitle;
        }
        
        return $arrayResult;
    }
    
    /**
     * 
     * @param type $arrayResult
     * @return Video
     */
    public function setVideoData($arrayResult) 
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
     * @return Video
     */
    public function getByYoutubeId($yurl)
    {        
        $arrayResult = $this->getArrayResult($yurl);
        $video = $this->setVideoData($arrayResult);
        
        return $video;
    }
    
    public function getVideos() {
        
        $nextPageToken = $nbPage = null;
        $page = 0;
        
        do {
            $subscriptionsURL = $this->baseURL . "subscriptions?part=snippet%2CcontentDetails&channelId=UCygqmomnv_HLr-Df0LlxsiQ&key=" . $this->developerKey;
            if ($nextPageToken) {
                $subscriptionsURL = $subscriptionsURL . '&pageToken=' . $nextPageToken;
            }
            
            $subscriptions = $this->getJSONObject($subscriptionsURL);
            
            if ($subscriptions) {                
                $nextPageToken = $this->getNextPageToken($subscriptions, $nextPageToken);

                $nbPage = $this->getNbPage($subscriptions, $nbPage);
                
                $this->fetchSubscriptions($subscriptions);
            }
            
            $page++;
        } while ($page < $nbPage);
        //} while ($page=0);
        
        $this->em->flush();
        
        $this->errorMessages[] = " ".$this->nbNewChannels." Channels";
        $this->errorMessages[] = " ".$this->nbNewApprovals." Approvals";
        
        return $this->errorMessages;
    }
    
    private function fetchSubscriptions($subscriptions = null) {                  

        foreach ($subscriptions->items as $channelItem) {
            $channelId = $channelItem->snippet->resourceId->channelId;
            $totalItemCount = $channelItem->contentDetails->totalItemCount;
            $channel = $this->em->getRepository('SpicyAppBundle:Channel')->findOneBy(['channelId' => $channelId]);

            if (!$channel) {
                $channel = new Channel();
                $channel->setTitle($channelItem->snippet->title)
                        ->setChannelId($channelId);
                
                $this->nbNewChannels++;
            }
            
            if($channel->getTotalItemCount() != $totalItemCount) { // si le total a changé
                $channel->setTotalItemCount($totalItemCount);

                $searchURL = $this->baseURL . "search?part=snippet&channelId=".$channelId.
                        "&maxResults=10&order=date&publishedAfter=2018-11-01T00%3A00%3A00Z&key=". $this->developerKey;

                $videos = $this->getJSONObject($searchURL);

                $this->searchVideos($videos);
            }
            
            $this->em->persist($channel);
        }        
    }
    
    private function searchVideos($videos) {               

        if($videos) {
            foreach ($videos->items as $videoItem) {
                if ($videoItem->id->kind == "youtube#video") {
                    $videoId = $videoItem->id->videoId;
                    
                    $this->getVideoData($videoId);                    
                }
            }
        }        
    }
    
    private function getVideoData($videoId) {        
        $videoJson = $this->getJSONResponse($videoId);

        if ($videoJson) {
            $duration = $this->covtime($videoJson->items[0]->contentDetails->duration);

            if ($duration > 120 && $duration < 600) { //entre 2 et 10 minutes
                
                $this->createApproval($videoJson);                                                   

                $this->em->flush();
            }
        }
    }
    
    private function createApproval($videoJson) {
        $id = $videoJson->items[0]->id;
        $video = $this->em->getRepository('SpicySiteBundle:Video')->findOneBy(['url' => $id]);        
        
        if (!$video) {
            $snippet = $videoJson->items[0]->snippet;

            $video = new Video();
            $video->setUrl($id)
                    ->setDescription(htmlspecialchars(utf8_decode($snippet->description)))
                    ->setTitre(htmlspecialchars(utf8_decode($snippet->title)))
                    ->setSource($snippet->channelTitle);                                                                                       

            $this->em->persist($video); 

            $approval = $this->approvalService->getDefaultApproval($video);

            $this->em->persist($approval); 
            
            $this->nbNewApprovals++;
        }
    }
    
    private function getNextPageToken($subscriptions, $nextPageToken = null) {
        
        if (isset($subscriptions->nextPageToken)) {                    
            $nextPageToken = $subscriptions->nextPageToken;
        }
                
        return $nextPageToken;
    }
    
    private function getNbPage($subscriptions, $nbPage = null) {
        if (!$nbPage) {  
            $totalResults = $subscriptions->pageInfo->totalResults;
            $this->errorMessages[] = " $totalResults abonnements";
            $resultsPerPage = $subscriptions->pageInfo->resultsPerPage;
            $nbPage = ceil($totalResults/$resultsPerPage);
        }
        
        return $nbPage;
    }
    
    public function covtime($youtube_time){
        $start = new \DateTime('@0'); // Unix epoch
        $start->add(new \DateInterval($youtube_time));
        return $start->getTimestamp();
    }   
}

