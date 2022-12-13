<?php

class Reponse
{

    // Attributs
    private $id; // int : identifiant de la réponse
    private $label; // string : libellé de la réponse
    private $isCorrect; // bool : indique si la réponse est correcte ou non

    // Constructeur
    public function __construct($id, $label, $isCorrect)
    {
        $this->id = $id;
        $this->label = $label;
        $this->isCorrect = $isCorrect;
    }

    // Getters
    public function getId()
    { return $this->id; }

    public function getLabel()
    { return $this->label; }

    public function getIsCorrect()
    { return $this->isCorrect; }

    // Setters
    public function setId($id)
    { $this->id = $id; }

    public function setLabel($label)
    { $this->label = $label; }

    public function setIsCorrect($isCorrect)
    { $this->isCorrect = $isCorrect; }

    // Méthodes
    public function __toString()
    {
        return "Reponse [id=" . $this->id . ", label=" . $this->label . ", isCorrect=" . $this->isCorrect . "]";
    }

    public function toHTML()
    {
        $html = "<div class='reponse'>";
        $html .= "<input type='radio' name='reponse' value='" . $this->id . "' />";
        $html .= "<label>" . $this->label . "</label>";
        $html .= "</div>";
        return $html;
    }

    public function toHTMLForCorrection()
    {
        $html = "<div class='reponse'>";
        $html .= "<input type='radio' name='reponse' value='" . $this->id . "' disabled='disabled' ";
        if ($this->isCorrect)
            $html .= "checked='checked' ";
        $html .= "/>";
        $html .= "<label>" . $this->label . "</label>";
        $html .= "</div>";
        return $html;
    }

}