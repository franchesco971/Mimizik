<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spicy\FluxBundle\Services;

use Spicy\SiteBundle\Entity\Video;

/**
 * Description of fluxService
 *
 * @author franciscopol
 */
class FluxService
{
    const MAX_TYPES = 117; //130-13
    const MAX_TYPES_TWITTER = 117; //130-13

    /**
     * Undocumented function
     *
     * @param Video[] $videos
     * @return string[]
     */
    public function fluxType($videos)
    {        
        $descriptions = array();

        foreach ($videos as $video) {
            $description = '';
            $nbTypeTitre =  strlen($this->getTitle($video));

            if ($nbTypeTitre < self::MAX_TYPES_TWITTER) {
                $description = $this->getDescriptionTag($video, $nbTypeTitre);
                $description = $this->getDescriptionHashtag($video, $nbTypeTitre, $description);
            }

            $descriptions[$video->getId()] = [
                'tags' => $description,
                'description' => $video->getDescription(),
            ];
        }

        return $descriptions;
    }

    /**
     * getDescriptionHashtag
     *
     * @param Video $video
     * @param integer $nbTitreTypes
     * @param string $description
     * @return void
     */
    public function getDescriptionHashtag(Video $video, int $nbTitreTypes, string $description)
    {
        $nbTypeHashtag = 0;
        
        if (strlen($description . ' #clip #mimizik ') >= self::MAX_TYPES) {
            return $description;
        }

        $description = $description . ' #clip #mimizik ';
        $nbTotalTypes = $nbTitreTypes + strlen($description);

        $arrayHastags = $this->getArrayHashtags($video);
        foreach ($arrayHastags as $hashtag) {
            $nbTotalTypes = $nbTotalTypes + $nbTypeHashtag;
            if ($nbTotalTypes < self::MAX_TYPES) {
                $nbTypeHashtag = $nbTypeHashtag + strlen($hashtag->getLibelle());
                $description = $description . '#' . $hashtag->getLibelle() . ' ';
            }
        }
        
        return $description;
    }

    /**
     * getArrayHashtags
     *
     * @param Video $video
     * @return array
     */
    public function getArrayHashtags(Video $video)
    {
        $tabHashtags = $video->getHashtags();

        foreach ($video->getArtistes() as $artiste) {
            foreach ($artiste->getHashtags() as $hashtag) {
                if (!$tabHashtags->contains($hashtag)) {
                    $tabHashtags->add($hashtag);
                }
            }
        }

        return $tabHashtags->toArray();
    }

    /**
     * getTitle
     *
     * @param Video $video
     * @return string
     */
    public function getTitle(Video $video): string
    {
        return $video->getNomArtistes() . ' - ' . $video->getTitre() . ': ';
    }

    /**
     * getDescriptionTag
     *
     * @param Video $video
     * @param integer $nbTotalTypes
     * @return string
     */
    public function getDescriptionTag(Video $video, $nbTotalTypes)
    {
        $nbTypeTwitterTag = 0;
        $description = '';

        $tabInstaCollabs = $this->getArrayInstaCollabs($video);
        $arrayTags = $this->getArrayInstaTags($video);
        $arrayTags = array_merge($tabInstaCollabs, $arrayTags);
        foreach ($arrayTags as $tag) {
            $nbTotalTypes = $nbTotalTypes + $nbTypeTwitterTag;
            if ($nbTotalTypes < self::MAX_TYPES_TWITTER) {
                $nbTypeTwitterTag = $nbTypeTwitterTag + strlen($tag);
                $description = $description . '@' . $tag . ' ';
            }
        }

        return $description;
    }

    /**
     * getArrayInstaTags
     *
     * @param Video $video
     * @return string[]
     */
    public function getArrayInstaTags(Video $video)
    {
        $tags = '';
        $tabTags = [];
        $artistes = $video->getArtistes();

        foreach ($artistes as $artiste) {
            if ($tags == '') {
                $tags = $tags . $artiste->getInstagram();
            } elseif ($artiste->getInstagram() != '') {
                $tags = $tags . ';' . $artiste->getInstagram();
            }
        }

        //@todo refacto tags
        // if ($tags == '') {
        //     $tags = $tags . $video->getTagsTwitter();
        // } elseif ($video->getTagsTwitter() != '') {
        //     $tags = $tags . ';' . $video->getTagsTwitter();
        // }

        if ($tags != '') {
            $tabTags = explode(";", $tags);
        }

        return $tabTags;
    }

    /**
     * getArrayInstagramCollabs
     *
     * @param Video $video
     * @return array
     */
    public function getArrayInstaCollabs(Video $video)
    {
        $tags = '';
        $tabTags = [];
        $collaborateurs = $video->getCollaborateurs();

        foreach ($collaborateurs as $collaborateur) {
            $instagram = $collaborateur->getInstagram();
            if ($tags == '') {
                $tags = $instagram;
            } elseif ($instagram != '') {
                $tags = $tags . ';' . $instagram;
            }
        }

        if ($tags != '') {
            $tabTags = explode(";", $tags);
        }

        return $tabTags;
    }
}
