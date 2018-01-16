<?php
namespace Spicy\SiteBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * @ORM\Table(name="title")
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"video" = "Video","track"="Track"})
 */
abstract class Title
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
     * @ORM\Column(name="url", type="string", length=255, unique=true)
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
     * @var boolean
     *
     * @ORM\Column(name="on_top", type="boolean")
     */
    private $onTop;
    
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
     * @var string
     *
     * @ORM\Column(name="tags_fb", type="string", length=255, nullable=true)
     */
    private $tags_fb;
    
    /**
     * @var string
     *
     * @ORM\Column(name="tags_twitter", type="string", length=255, nullable=true)
     */
    private $tags_twitter;
    
    /**
    * @ORM\ManyToMany(targetEntity="Spicy\TagBundle\Entity\Hashtag")
    * @ORM\JoinTable(name="video_hashtag",
    *      joinColumns={@ORM\JoinColumn(name="video_id", referencedColumnName="id")},
    *      inverseJoinColumns={@ORM\JoinColumn(name="hashtag_id", referencedColumnName="id")}
    *      )
    * @ORM\OrderBy({"libelle" = "ASC"})
    * @Assert\Valid()
    */
    private $hashtags;
    
    /**
    * @ORM\ManyToMany(targetEntity="Spicy\SiteBundle\Entity\Artiste")
    * @ORM\JoinTable(name="video_artiste",
    *      joinColumns={@ORM\JoinColumn(name="video_id", referencedColumnName="id")},
    *      inverseJoinColumns={@ORM\JoinColumn(name="artiste_id", referencedColumnName="id")}
    *      )
    * @ORM\OrderBy({"libelle" = "ASC"})
    * @Assert\Valid()
    */
    private $artistes;
    
    /**
    * @ORM\ManyToMany(targetEntity="Spicy\SiteBundle\Entity\GenreMusical")
    * @ORM\JoinTable(name="video_genremusical",
    *      joinColumns={@ORM\JoinColumn(name="video_id", referencedColumnName="id")},
    *      inverseJoinColumns={@ORM\JoinColumn(name="genremusical_id", referencedColumnName="id")}
    *      )
    * @ORM\OrderBy({"libelle" = "ASC"}) 
    * @Assert\Valid()
    */
    private $genre_musicaux;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="nextPublishDate", type="datetime", nullable=true)
     */
    private $nextPublishDate;
    
    /**
    * @ORM\ManyToMany(targetEntity="Spicy\SiteBundle\Entity\Collaborateur")
    * @ORM\JoinTable(name="video_collaborateur",
    *      joinColumns={@ORM\JoinColumn(name="video_id", referencedColumnName="id")},
    *      inverseJoinColumns={@ORM\JoinColumn(name="collaborateur_id", referencedColumnName="id")}
    *      )
    * @ORM\OrderBy({"name" = "ASC"})
    * @Assert\Valid()
    */
    private $collaborateurs;
    
    /**
     * @ORM\OneToOne(targetEntity="Spicy\LyricsBundle\Entity\Lyrics", inversedBy="title", cascade={"persist"})
     * @ORM\JoinColumn(name="lyrics_id", referencedColumnName="id")
     */
    private $lyrics;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dateVideo=new \DateTime;
        $this->artistes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->genre_musicaux = new \Doctrine\Common\Collections\ArrayCollection();
        $this->collaborateurs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->etat=true;
        $this->onTop=false;
        $this->source='youtube';
    }
    
    public function getNomArtistes()
    {
        $noms='';
        
        if(count($this->artistes))
        {
            foreach ($this->artistes as $key => $artiste) 
            {
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
    /**
     * Set tags_fb
     *
     * @param string $tagsFb
     * @return Video
     */
    public function setTagsFb($tagsFb)
    {
        $this->tags_fb = $tagsFb;
    
        return $this;
    }
    /**
     * Get tags_fb
     *
     * @return string 
     */
    public function getTagsFb()
    {
        return $this->tags_fb;
    }
    /**
     * Set tags_twitter
     *
     * @param string $tagsTwitter
     * @return Video
     */
    public function setTagsTwitter($tagsTwitter)
    {
        $this->tags_twitter = $tagsTwitter;
    
        return $this;
    }
    /**
     * Get tags_twitter
     *
     * @return string 
     */
    public function getTagsTwitter()
    {
        return $this->tags_twitter;
    }
    /**
     * Set onTop
     *
     * @param boolean $onTop
     * @return Video
     */
    public function setOnTop($onTop)
    {
        $this->onTop = $onTop;
    
        return $this;
    }
    /**
     * Get onTop
     *
     * @return boolean 
     */
    public function getOnTop()
    {
        return $this->onTop;
    }
    /**
     * Add hashtags
     *
     * @param \Spicy\TagBundle\Entity\Hashtag $hashtags
     * @return Video
     */
    public function addHashtag(\Spicy\TagBundle\Entity\Hashtag $hashtags)
    {
        $this->hashtags[] = $hashtags;
    
        return $this;
    }
    /**
     * Remove hashtags
     *
     * @param \Spicy\TagBundle\Entity\Hashtag $hashtags
     */
    public function removeHashtag(\Spicy\TagBundle\Entity\Hashtag $hashtags)
    {
        $this->hashtags->removeElement($hashtags);
    }
    /**
     * Get hashtags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHashtags()
    {
        return $this->hashtags;
    }
    
    /**
     * Set nextPublishDate
     *
     * @param \DateTime $nextPublishDate
     * @return Video
     */
    public function setNextPublishDate($nextPublishDate)
    {
        $this->nextPublishDate = $nextPublishDate;
    
        return $this;
    }
    /**
     * Get nextPublishDate
     *
     * @return \DateTime 
     */
    public function getNextPublishDate()
    {
        return $this->nextPublishDate;
    }
    /**
     * Add collaborateurs
     *
     * @param \Spicy\SiteBundle\Entity\Collaborateur $collaborateurs
     * @return Video
     */
    public function addCollaborateur(\Spicy\SiteBundle\Entity\Collaborateur $collaborateurs)
    {
        $this->collaborateurs[] = $collaborateurs;
    
        return $this;
    }
    /**
     * Remove collaborateurs
     *
     * @param \Spicy\SiteBundle\Entity\Collaborateur $collaborateurs
     */
    public function removeCollaborateur(\Spicy\SiteBundle\Entity\Collaborateur $collaborateurs)
    {
        $this->collaborateurs->removeElement($collaborateurs);
    }
    /**
     * Get collaborateurs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCollaborateurs()
    {
        return $this->collaborateurs;
    }

    /**
     * Set lyrics
     *
     * @param \Spicy\LyricsBundle\Entity\Lyrics $lyrics
     * @return Title
     */
    public function setLyrics(\Spicy\LyricsBundle\Entity\Lyrics $lyrics = null)
    {
        $this->lyrics = $lyrics;

        return $this;
    }

    /**
     * Get lyrics
     *
     * @return \Spicy\LyricsBundle\Entity\Lyrics 
     */
    public function getLyrics()
    {
        return $this->lyrics;
    }
}
