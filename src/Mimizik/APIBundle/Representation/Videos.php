<?php

namespace Mimizik\APIBundle\Representation;

use Pagerfanta\Pagerfanta;
use JMS\Serializer\Annotation as Serializer;
use Spicy\SiteBundle\Entity\Video;

/**
 * Description of Video
 *
 * @author franciscopol
 */
class Videos extends EntityRepresentation
{
    /**
     * @Serializer\Type("array<Spicy\SiteBundle\Entity\Video>")
     * @Serializer\SerializedName("videos")
     */
    public $data;

    public $meta;
}
