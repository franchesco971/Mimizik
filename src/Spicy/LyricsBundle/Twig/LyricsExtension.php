<?php

namespace Spicy\LyricsBundle\Twig;

use Spicy\LyricsBundle\Services\LyricsService;
use Spicy\LyricsBundle\Entity\Paragraph;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LyricsExtension
 *
 * @author franciscopol
 */
class LyricsExtension extends \Twig_Extension
{
    private $lyricsService;
    
    public function __construct(LyricsService $lyricsService)
    {
        $this->lyricsService = $lyricsService;
    }
    
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('paragraphType', array($this, 'paragraphTypeFilter')),
        );
    }
    
    public function paragraphTypeFilter(Paragraph $paragraph)
    {
        return $this->lyricsService->getParagrphType($paragraph->getParagraphType());
    }
}
