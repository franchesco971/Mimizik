<?php

namespace Spicy\LyricsBundle\Twig;

use Spicy\LyricsBundle\Services\LyricsService;
use Spicy\LyricsBundle\Entity\Paragraph;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Description of LyricsExtension
 *
 * @author franciscopol
 */
class LyricsExtension extends AbstractExtension
{
    private $lyricsService;
    
    public function __construct(LyricsService $lyricsService)
    {
        $this->lyricsService = $lyricsService;
    }
    
    public function getFilters()
    {
        return [
            new TwigFilter('paragraphType', [$this, 'paragraphTypeFilter']),
        ];
    }
    
    public function paragraphTypeFilter(Paragraph $paragraph)
    {
        return $this->lyricsService->getParagrphType($paragraph->getParagraphType());
    }
}
