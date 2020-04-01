<?php

namespace Spicy\SiteBundle\Twig;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Spicy\SiteBundle\Entity\Artiste;

class NameExtension extends AbstractExtension
{
    private $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    public function getFilters()
    {
        return array(
            new TwigFilter('artistsName', array($this, 'artistsNameFilter')),
            new TwigFilter('artistsNameLink', array($this, 'artistsNameLinkFilter')),
        );
    }

    /**
     * artistsNameLinkFilter
     *
     * @param Artist[] $artists
     * @param integer $maxNumber
     * @return string
     */
    public function artistsNameLinkFilter($artists, $maxNumber = 100)
    {
        $text = '';
        $nbletter = 0;
        foreach ($artists as $key => $artist) {
            if ($nbletter >= $maxNumber) {
                continue;
            }

            $link = $this->router->generate('spicy_site_artiste_slug', ['id' => $artist->getId(), 'slug' => $artist->getSlug()]);
            $label = $artist->getLibelle();
            $nbletter = $nbletter + strlen($label);
            $block = "<a title='$label' href='$link'>$label</a>";
            $text = $text . $block;
            $text = $this->ponctuation($artists, $key, $text);
        }

        return $text;
    }

    /**
     * artistsNameFilter
     *
     * @param Artist[] $artists
     * @param integer $maxNumber
     * @return string
     */
    public function artistsNameFilter($artists, int $maxNumber = 100)
    {
        $text = '';
        $nbletter = 0;
        foreach ($artists as $key => $artist) {
            if ($nbletter >= $maxNumber) {
                continue;
            }

            $label = $artist->getLibelle();
            $nbletter = $nbletter + strlen($label);
            $text = $text . $label;
            $text = $this->ponctuation($artists, $key, $text);            
        }

        return $text;
    }

    public function ponctuation($artists, $key, $text)
    {
        if (count($artists) == 2 && $key == 0) {
            $text = $text . ' &amp; ';
        } elseif (count($artists) > 2 && count($artists) - $key > 2) {
            $text = $text . ', ';
        } elseif (count($artists) - $key == 2) {
            $text = $text . ' &amp; ';
        }

        return $text;
    }

    public function getName()
    {
        return 'name.extension';
    }
}
