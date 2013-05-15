<?php

namespace Spicy\SiteBundle\ParseurXML;

use \DOMDocument as DomDocument;
use Spicy\SiteBundle\Entity\News as News;

class ParseurNews
{
    private $document;
    //private $tabResultat;
    
    public function __construct($document='')
    {
        $this->document=$document;
    }
    
    public function setDocument($document)
    {
        $this->document=$document;
    }
            
    function parsage()
    {
        $document_xml = new DomDocument;
        //$document_xml->load($document);
        $document_xml->load($this->document);
        $listeItems = $document_xml->getElementsByTagName('item');/*** les items*/
        
        //foreach($listeItems as $item)
        for ($j = 0; $j < $listeItems->length; ++$j)    
        {
            $item=$listeItems->item($j);/*** un item*/
            $news= new News();
            //for ($i = 0; $i < $item->length; ++$i)
            //foreach($item->childNodes as $node)
            for ($i = 0; $i < $item->childNodes->length; ++$i)
            {
               $itemNodes=$item->childNodes;
               $node=$itemNodes->item($i);
               $valeur=$itemNodes->item($i)->nodeValue;
               
                if($node->nodeName=='title')
               {
                   $news->setTitre($valeur);
               }
               
               if($node->nodeName=='link')
               {
                   $news->setUrl($valeur);
               }
               
               if($node->nodeName=='description')
               {
                   $news->setDescription($valeur);
               }
               
               if($node->nodeName=='pubDate')
               {
                   $news->setDateNews($valeur);
               }
            }
            $tabResultat[]=$news;
           
        }
        
        
        //$resultat_html = '';
        //$arbre = $elements->item(0);

        return $tabResultat;
    }
    
    
}
