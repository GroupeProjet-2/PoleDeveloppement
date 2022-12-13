<?php

class Question
{

    // Attributs
    private $id; // int : identifiant de la question
    private $enonce; // string : énoncé de la question
    private $mesReponse; // array : tableau de réponses possibles
    private $type; // string : type de la question (QCM, QCU, QO)
    private $difficulte; // int : difficulté de la question (enum)
    private $desTags; // array : tableau de tags associés à la question

    // Constructeur
    public function __construct($id, $enonce, $type, $difficulte)
    {
        $this->id = $id;
        $this->enonce = $enonce;
        $this->type = $type;
        $this->difficulte = $difficulte;
    }

    // Getters
    public function getId()
    { return $this->id; }

    public function getEnonce()
    { return $this->enonce; }

    public function getMesReponse()
    { return $this->mesReponse; }

    public function getType()
    { return $this->type; }

    public function getDifficulte()
    { return $this->difficulte; }

    public function getDesTags()
    { return $this->desTags; }

    public function getNbReponses()
    { return count($this->mesReponse); }

    public function getNbReponsesCorrectes()
    {
        $nb = 0;
        foreach ($this->mesReponse as $uneReponse)
        {
            if ($uneReponse->getIsCorrect())
                $nb++;
        }
        return $nb;
    }


    // Setters
    public function setId($id)
    { $this->id = $id; }

    public function setEnonce($enonce)
    { $this->enonce = $enonce; }

    public function setMesReponse($mesReponse)
    { $this->mesReponse = $mesReponse; }

    public function setType($type)
    { $this->type = $type; }

    public function setDifficulte($difficulte)
    { $this->difficulte = $difficulte; }

    public function setDesTags($desTags)
    { $this->desTags = $desTags; }

    public function addReponse($uneReponse)
    { $this->mesReponse[] = $uneReponse; }

    public function addTag($unTag)
    { $this->desTags[] = $unTag; }

    // Méthodes
    public function __toString()
    {
        $str = "Question [id=" . $this->id . ", enonce=" . $this->enonce . ", type=" . $this->type . ", difficulte=" . $this->difficulte . "]";
        $str .= " [mesReponse=";
        foreach ($this->mesReponse as $uneReponse) {
            $str .= $uneReponse . ", ";
        }
        $str .= "]";
        $str .= " [desTags=";
        foreach ($this->desTags as $unTag) {
            $str .= $unTag . ", ";
        }
        $str .= "]";
        return $str;
    }

    public function toHTML()
    {
        $html = "<div class='question'>";
        $html .= "<h3>" . $this->enonce . "</h3>";
        $html .= "<div class='reponses'>";
        foreach ($this->mesReponse as $uneReponse) {
            $html .= $uneReponse->toHTML();
        }
        $html .= "</div>";
        $html .= "</div>";
        return $html;
    }

    public function toHTMLForCorrection()
    {
        $html = "<div class='question'>";
        $html .= "<h3>" . $this->enonce . "</h3>";
        $html .= "<div class='reponses'>";
        foreach ($this->mesReponse as $uneReponse) {
            $html .= $uneReponse->toHTMLForCorrection();
        }
        $html .= "</div>";
        $html .= "</div>";
        return $html;
    }








}