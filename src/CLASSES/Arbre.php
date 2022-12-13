<?php

// Class : Arbre
// Path: PoleDeveloppement\src\CLASSES\Arbre.php
// Il s'agit d'un arbre pouvant contenir les niveaux de chaque tag et les tags eux-mêmes,
// pour chaque tag, on a un statut (enum) qui peut être :
// FACILE, MOYEN_1, MOYEN_2, DIFFICILE
// Il permet de connaitre ou se situe l'utilisateur dans l'arbre

class Arbre
{

    // Attributs
    private $id; // int : identifiant de l'arbre
    private $monArbre; // array : Map de l'arbre

    // Constructeur
    public function __construct($id)
    {
        $this->id = $id;
        $this->monArbre = array();
    }

    // Getters
    public function getId()
    { return $this->id; }

    public function getMonArbre()
    { return $this->monArbre; }

    // Setters
    public function setId($id)
    { $this->id = $id; }

    public function setMonArbre($monArbre)
    { $this->monArbre = $monArbre; }

    public function addNiveau($niveau)
    {
        if (!array_key_exists($niveau, $this->monArbre))
            $this->monArbre[$niveau] = array();
    }

    public function addTag($niveau, $tag)
    {
        $this->monArbre[$niveau][$tag] = "FACILE";
    }

    public function setStatutTag($niveau, $tag, $statut)
    {
        $this->monArbre[$niveau][$tag] = $statut;
    }

    public function getStatutTag($niveau, $tag)
    {
        return $this->monArbre[$niveau][$tag];
    }

    public function getNiveau($niveau)
    {
        return $this->monArbre[$niveau];
    }

    public function getTags($niveau)
    {
        return array_keys($this->monArbre[$niveau]);
    }

    public function getNbNiveaux()
    {
        return count($this->monArbre);
    }

    public function getNbTags($niveau)
    {
        return count($this->monArbre[$niveau]);
    }

    // Méthodes
    public function toString()
    {
        $str = "Arbre : " . $this->id . " : ";
        foreach ($this->monArbre as $niveau => $tags) {
            $str .= "Niveau " . $niveau . " : ";
            foreach ($tags as $tag => $statut) {
                $str .= $tag . " (" . $statut . ") ";
            }
        }
        return $str;
    }







}