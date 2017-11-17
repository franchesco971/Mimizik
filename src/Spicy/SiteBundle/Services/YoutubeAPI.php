<?php

namespace Spicy\SiteBundle\Services;

use Spicy\SiteBundle\Entity\Video;

/**
 * 
 */
class YoutubeAPI
{
    private $developerKey;
    
    private $logger;
    
    public function __construct($logger, $developerKey)
    {
        $this->developerKey = $developerKey;
        $this->logger = $logger;
    }
    
    public function getJSONResponse($videoId) 
    {
        $url = "https://www.googleapis.com/youtube/v3/videos?id=$videoId&key=".$this->developerKey."&part=snippet,topicDetails";
        $obj = null;
        
        try {
            $json = file_get_contents($url);
            if($json == false) {
                throw new Exception("Pas de données");                
            }
            
            $obj = json_decode($json);
            
        } catch (Exception $ex) {
            $this->logger->error($ex->getMessage());
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
            $snippet=$objJSON->items[0]->snippet;
            $arrayResult['id']=$objJSON->items[0]->id;
            $arrayResult['title']=$snippet->title;
            $arrayResult['description']=$snippet->description;
            $arrayResult['channelTitle']=$snippet->channelTitle;
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
}

