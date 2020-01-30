<?php

namespace Spicy\FluxBundle\Services;

use Spicy\SiteBundle\Entity\Video;

class Twitter extends FluxService
{    
    public function twitterType($videos)
    {       
        $descriptions = array();

        foreach ($videos as $video) {
            $description = '';
            $nbTypeTitre =  strlen($this->getTitle($video));

            if ($nbTypeTitre < self::MAX_TYPES_TWITTER) {
                $description = $this->getDescriptionTwitterTag($video, $nbTypeTitre);
                $description = $this->getDescriptionHashtag($video, $nbTypeTitre, $description);
            }

            $descriptions[$video->getId()] = $description;
        }

        return $descriptions;
    }

    /**
     * getArrayTwitterTags
     *
     * @param Video $video
     * @return string[]
     */
    public function getArrayTwitterTags(Video $video)
    {
        $tags = '';
        $tabTags = [];

        foreach ($video->getArtistes() as $artiste) {
            if ($tags == '') {
                $tags = $tags . $artiste->getTagTwitter();
            } elseif ($artiste->getTagTwitter() != '') {
                $tags = $tags . ';' . $artiste->getTagTwitter();
            }
        }

        if ($tags == '') {
            $tags = $tags . $video->getTagsTwitter();
        } elseif ($video->getTagsTwitter() != '') {
            $tags = $tags . ';' . $video->getTagsTwitter();
        }

        if ($tags != '') {
            $tabTags = explode(";", $tags);
        }

        return $tabTags;
    }

    public function getDescriptionTwitterTag(Video $video, $nbTotalTypes)
    {
        $nbTypeTwitterTag = 0;
        $description = '';

        $arrayTwitterCollabs = $this->getArrayTwitterCollabs($video);
        $arrayTwitterTags = $this->getArrayTwitterTags($video);
        $arrayTwitterTags = array_merge($arrayTwitterCollabs, $arrayTwitterTags);
        foreach ($arrayTwitterTags as $twitterTag) {
            $nbTotalTypes = $nbTotalTypes + $nbTypeTwitterTag;
            if ($nbTotalTypes < self::MAX_TYPES_TWITTER) {
                $nbTypeTwitterTag = $nbTypeTwitterTag + strlen($twitterTag);
                $description = $description . '@' . $twitterTag . ' ';
            }
        }

        return $description;
    }    

    public function getArrayTwitterCollabs(Video $video)
    {
        $tags = '';
        $tabTags = [];

        foreach ($video->getCollaborateurs() as $collaborateur) {
            if ($tags == '') {
                $tags = $collaborateur->getTwitter();
            } elseif ($collaborateur->getTwitter() != '') {
                $tags = $tags . ';' . $collaborateur->getTwitter();
            }
        }

        if ($tags != '') {
            $tabTags = explode(";", $tags);
        }

        return $tabTags;
    }
}
?>
