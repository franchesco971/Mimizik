<?php

namespace Spicy\SiteBundle\Services;

use \DOMDocument as DomDocument;
use Spicy\SiteBundle\Entity\News as News;

class ParseurXMLYoutube
{
    private $document;
    private $link;
    
    public function __construct($link='')
    {
        $this->link=$link;
        
    }
    
    public function setDocument($link)
    {
        $this->link=$link;
        $this->document=$this->loadDocument();
    }
    
    public function loadDocument() 
    {
        $document_xml = new DomDocument;
        $document_xml->load($this->link);
        
        return $document_xml;
    }
    
    public function get($property='content') 
    {
        return $this->document->getElementsByTagName($property)->item(0)->nodeValue;
    }
}