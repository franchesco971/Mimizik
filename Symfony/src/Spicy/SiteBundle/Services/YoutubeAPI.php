<?php

namespace Spicy\SiteBundle\Services;

use Spicy\SiteBundle\Entity\Video;

class YoutubeAPI
{
    public function getJSONResponse($videoId,$developerKey) 
    {
        //$videoId='KGzlYk8YXTA';
        //$developerKey='AIzaSyC8pNw1O9dsx8X1YKVpjHTcuEyx5pB3M44';
        $url="https://www.googleapis.com/youtube/v3/videos?id=$videoId&key=$developerKey&part=snippet,topicDetails";
        $json = file_get_contents($url);
        $obj = json_decode($json);
        
        return $obj;
    }
    
    public function getArrayResult($videoId, $developerKey) 
    {
        $arrayResult= array();
        $objJSON=$this->getJSONResponse($videoId, $developerKey);
        
        $snippet=$objJSON->items[0]->snippet;
        $arrayResult['id']=$objJSON->items[0]->id;
        $arrayResult['title']=$snippet->title;
        $arrayResult['description']=$snippet->description;
        $arrayResult['channelTitle']=$snippet->channelTitle;
        
        return $arrayResult;
    }
    
    public function setVideoData(Video $video,$arrayResult) 
    {
        $video->setUrl($arrayResult['id']);
        $video->SetDescription($arrayResult['description']);
        $video->setTitre($arrayResult['title']);
        $video->setSource($arrayResult['channelTitle']);
                
        return $video;
    }
}

