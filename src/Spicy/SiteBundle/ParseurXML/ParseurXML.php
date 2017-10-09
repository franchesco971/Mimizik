<?php

namespace Spicy\SiteBundle\ParseurXML;

use \DOMDocument as DomDocument;

class ParseurXML
{
    private $document;
    
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
        $elements = $document_xml->getElementsByTagName('channel');
        $resultat_html = '';
        $arbre = $elements->item(0);

        $resultat_html = $this->parsage_enfant($arbre);

        return $resultat_html;
    }
    
    function parsage_normal($noeud, $contenu_a_inserer='')
    {
         $balise_1 = array('gras' => '<strong>',
                                    'italique' => '<span class="italique">',
                                    'position' => '<div class="$1">',
                                    'flottant' => '<div class="flot_$1">',
                                    'taille' => '<span class="$1">',
                                    'couleur' => '<span class="$1">',
                                    'police' => '<span class="$1">',
                                    'attention' => '<span class="rmq $1">',
                                    'liste' => '<ul>',
                                    'puce' => '<li>',
                                    'lien' => '<a href="$1">',
                                    'image' => '<img src="$1" alt="$2" />',
                                    'citation' => '<span class="citation">',
                                    '#text' => ''); // Tableau des balises ouvrantes

        $balise_2 = array('gras' => '</strong>',
                                        'italique' => '</span>',
                                        'position' => '</div>',
                                        'flottant' => '</div>',
                                        'taille' => '</span>',
                                        'couleur' => '</span>',
                                        'police' => '</span>',
                                        'attention' => '</span>',
                                        'information' => '</span>',
                                        'liste' => '</ul>',
                                        'puce' => '</li>',
                                        'lien' => '</a>',
                                        'image' => '',
                                        'citation' => '</span>',
                                        '#text' => ''); // Tableau des balises fermantes

        $attributs = array('position' => 'valeur',
                                        'flottant' => 'valeur',
                                        'taille' => 'valeur',
                                        'couleur' => 'nom',
                                        'police' => 'nom',
                                        'lien' => 'url',
                                        'image' => 'legende',
                                        'citation' => 'auteur'); // Tableau des attributs

        $nom = $noeud->nodeName; // On récupère le nom du nœud

        if(!empty($contenu_a_inserer)) // On détermine si on veut spécifier du contenu préparsé
        {
                $contenu = $contenu_a_inserer; // Si c'est le cas, on met la variable de fonction en contenu
        }
        else
        {
                $contenu = $noeud->nodeValue; // Sinon, le contenu du nœud
        }

        //******$premiere_balise = $balise_1[$nom];     // Première balise (ouvrante)
        if(isset($balise_1[$nom]))
        {
            $premiere_balise = $balise_1[$nom];
        }
        else
        {
            $premiere_balise = '<p>';
        }

        if($noeud->hasAttributes() && $nom != 'image') // On remplace les attributs (sauf pour les images)
        {             
            /****** a voir
                $un = $noeud->attributes->getNamedItem($attributs[$nom])->nodeValue; // Récupération de la valeur de l'attribut
                $premiere_balise = str_replace("$1", $un, $premiere_balise); // On remplace la valeur $1 par celle de l'attribut
            */
        }

        if($nom == 'image') // Cas particulier des images

        {
                $un = $contenu; // Dans ce cas, c'est $1 qui récupère le contenu du nœud (l'URL de l'image).
                $premiere_balise = str_replace("$1", $un, $premiere_balise);

                if($noeud->hasAttributes()) // Si l'image contient une légende (attribut $2)

                {
                        $deux = $noeud->attributes->getNamedItem('legende')->nodeValue; // Récupération de l'attribut « legende »
                }
                else // Par défaut, la légende (alt) est « Image »
                {
                        $deux = 'Image';
                }

                $premiere_balise = str_replace("$2", $deux, $premiere_balise);
                $intermediaire = $premiere_balise;

        }
        else // Cas général
        {
            //*****$intermediaire = $premiere_balise . $contenu . $balise_2[$nom]; // On assemble le tout
            if(isset($balise_2[$nom]))
            {
                $intermediaire = $premiere_balise . $contenu . $balise_2[$nom];
            }
            else
            {
                $intermediaire = $premiere_balise . $contenu . '</p>';
            }
               
               
                if($nom == 'liste'  or $nom == 'puce')
                {
                        $intermediaire = preg_replace("#<ul>(\s)*<li>#sU", "<ul><li>", $intermediaire);
                        $intermediaire = preg_replace("#</li>(\s)*<li>#sU", "</li><li>", $intermediaire);
                        $intermediaire = preg_replace("#</li>(\s)*</ul>#sU", "</li></ul>", $intermediaire);
                }

                if($nom == 'zcode')
                {
                        $intermediaire = nl2br($intermediaire); // On saute des lignes au résultat final
                }
        }
        return $intermediaire; // On renvoie le texte parsé
    }
    
    function parsage_enfant($noeud)// Fonction de parsage d'enfants
    {
        if(!isset($accumulation)) // Si c'est la première balise, on initialise $accumulation
        {
                $accumulation = '';
        }

        $enfants_niv1 = $noeud->childNodes; // Les enfants du nœud traité

        //foreach($enfants_niv1 as $enfant) // Pour chaque enfant, on vérifie…
        /*for($i=1;$i<=10;$i++)
        {
            $enfant=$enfants_niv1[$i];  
            if($enfant->hasChildNodes() == true) // …s'il a lui-même des enfants
                {
                        $accumulation .= $this->parsage_enfant($enfant); // Dans ce cas, on revient sur parsage_enfant
                }
                else // ... s'il n'en a plus !
                {
                        $accumulation .= $this->parsage_normal($enfant); // On parse comme un nœud normal
                }
        }*/
        return $this->parsage_normal($noeud, $accumulation);
    }
}
