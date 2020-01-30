<?php

namespace Spicy\LyricsBundle\Services;

use Spicy\LyricsBundle\Entity\Paragraph;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LyricsService
 *
 * @author franciscopol
 */
class LyricsService
{
    public function getParagrphType($paragraphType)
    {
        $paragraphTypes = [Paragraph::INTRO => 'Intro', Paragraph::COUPLET => 'Couplet',  Paragraph::REFRAIN => 'Refrain',  Paragraph::OUTRO => 'Outro'];

        return $paragraphTypes[$paragraphType];
    }
}
