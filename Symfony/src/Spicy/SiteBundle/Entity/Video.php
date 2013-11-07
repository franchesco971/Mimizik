<?php

namespace Spicy\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Video
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Spicy\SiteBundle\Entity\VideoRepository")
 */
class Video
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateVideo", type="datetime")
     */
    private $dateVideo;

    /**
     * @var boolean
     *
     * @ORM\Column(name="etat", type="boolean")
     */
    private $etat;
    
    /**
     *
     * @var string
     * @Gedmo\Slug(fields={"titre"})
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;
    
    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=255)
     */
    private $source;
    
    /**
     * @var text
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;
    
    
    /**
    * @ORM\ManyToMany(targetEntity="Spicy\SiteBundle\Entity\Artiste")
     * @Assert\Valid()
    */
    private $artistes;
    
    /**
    * @ORM\ManyToMany(targetEntity="Spicy\SiteBundle\Entity\GenreMusical")
     * @Assert\Valid()
    */
    private $genre_musicaux;
    
    /**
    * @ORM\ManyToMany(targetEntity="Spicy\SiteBundle\Entity\TypeVideo", cascade={"persist"})
     * @Assert\Valid()
    */
    private $type_videos;
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dateVideo=new \DateTime;
        $this->artistes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->genre_musicaux = new \Doctrine\Common\Collections\ArrayCollection();
        $this->etat=true;
    }
    
    public function getNomArtistes()
    {
        $noms='';
        
        if(count($this->artistes))
        {
            foreach ($this->artistes as $key => $artiste) {
                
                //$noms=$noms.'<a href="{{path(\'spicy_site_artiste\',{\'id\':'.$artiste->getId().')}}">'.$artiste->getLibelle().'</a>';
                $noms=$noms.$artiste->getLibelle();
                
                if($key==count($this->artistes)-2)
                {
                    $noms=$noms.' & ';
                }                
                elseif($key!=count($this->artistes)-1)
                {
                    $noms=$noms.', ';
                }
            }
        }
        else{
            $noms='Inconnu';
        }
        
        return $noms;
    }
    
    public function getNomGenres()
    {
        $noms='';
        
        if(count($this->genre_musicaux))
        {
            foreach ($this->genre_musicaux as $key => $genre) {
                $noms=$noms.$genre->getLibelle();
                                
                if($key!=count($this->genre_musicaux)-1)
                {
                    $noms=$noms.', ';
                }
            }
        }
        else{
            $noms='Inconnu';
        }
        
        return $noms;
    }
    
    public function getNomTypes()
    {
        $noms='';
        
        if(count($this->type_videos))
        {
            foreach ($this->type_videos as $key => $type) {
                $noms=$noms.$type->getLibelle();
                                
                if($key!=count($this->type_videos)-1)
                {
                    $noms=$noms.', ';
                }
            }
        }
        else{
            $noms='Inconnu';
        }
        
        return $noms;
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set titre
     *
     * @param string $titre
     * @return Video
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    
        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Video
     */
    public function setUrl($url)
    {
        $this->url = $url;
    
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set dateVideo
     *
     * @param \DateTime $dateVideo
     * @return Video
     */
    public function setDateVideo($dateVideo)
    {
        $this->dateVideo = $dateVideo;
    
        return $this;
    }

    /**
     * Get dateVideo
     *
     * @return \DateTime 
     */
    public function getDateVideo()
    {
        return $this->dateVideo;
    }

    /**
     * Set etat
     *
     * @param boolean $etat
     * @return Video
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;
    
        return $this;
    }

    /**
     * Get etat
     *
     * @return boolean 
     */
    public function getEtat()
    {
        return $this->etat;
    }
    
    
    /**
     * Add artistes
     *
     * @param \Spicy\SiteBundle\Entity\Artiste $artistes
     * @return Video
     */
    public function addArtiste(\Spicy\SiteBundle\Entity\Artiste $artistes)
    {
        $this->artistes[] = $artistes;
    
        return $this;
    }

    /**
     * Remove artistes
     *
     * @param \Spicy\SiteBundle\Entity\Artiste $artistes
     */
    public function removeArtiste(\Spicy\SiteBundle\Entity\Artiste $artistes)
    {
        $this->artistes->removeElement($artistes);
    }

    /**
     * Get artistes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArtistes()
    {
        return $this->artistes;
    }

    /**
     * Add genre_musicaux
     *
     * @param \Spicy\SiteBundle\Entity\Genre_musical $genreMusicaux
     * @return Video
     */
    

    /**
     * Add genre_musicaux
     *
     * @param \Spicy\SiteBundle\Entity\GenreMusical $genreMusicaux
     * @return Video
     */
    public function addGenreMusicaux(\Spicy\SiteBundle\Entity\GenreMusical $genreMusicaux)
    {
        $this->genre_musicaux[] = $genreMusicaux;
    
        return $this;
    }

    /**
     * Remove genre_musicaux
     *
     * @param \Spicy\SiteBundle\Entity\GenreMusical $genreMusicaux
     */
    public function removeGenreMusicaux(\Spicy\SiteBundle\Entity\GenreMusical $genreMusicaux)
    {
        $this->genre_musicaux->removeElement($genreMusicaux);
    }

    /**
     * Get genre_musicaux
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGenreMusicaux()
    {
        return $this->genre_musicaux;
    }

    /**
     * Add type_videos
     *
     * @param \Spicy\SiteBundle\Entity\TypeVideo $typeVideos
     * @return Video
     */
    public function addTypeVideo(\Spicy\SiteBundle\Entity\TypeVideo $typeVideos)
    {
        $this->type_videos[] = $typeVideos;
    
        return $this;
    }

    /**
     * Remove type_videos
     *
     * @param \Spicy\SiteBundle\Entity\TypeVideo $typeVideos
     */
    public function removeTypeVideo(\Spicy\SiteBundle\Entity\TypeVideo $typeVideos)
    {
        $this->type_videos->removeElement($typeVideos);
    }

    /**
     * Get type_videos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTypeVideos()
    {
        return $this->type_videos;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Video
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set source
     *
     * @param string $source
     * @return Video
     */
    public function setSource($source)
    {
        $this->source = $source;
    
        return $this;
    }

    /**
     * Get source
     *
     * @return string 
     */
    public function getSource()
    {        
        return $this->source;
    }
    
    public function getTxtSource()
    {
        $txt='http://www.youtube.com/user/'.$this->source;
        return $txt;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Video
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }


}