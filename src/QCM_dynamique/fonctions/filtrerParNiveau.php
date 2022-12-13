<?php

    // Fonction qui retourne la liste des questions utilisables pour un niveau donné
    function questionNiveauX($niveau, $arbre, $banqueQ){
        $questionNiveauX = $banqueQ;
        $tagInutile = array();

        // On récupère les tags inutiles
        // Pour chaque tag on regarde le niveau et on regarde si le niveau est inférieur ou égal au niveau actuel
        foreach($arbre->getNiveau($niveau) as $tag => $statut){
            if($statut == "FACILE"){
                $tagInutile[] = $tag;
            }
        }

        // On filtre les questions
        foreach($questionNiveauX as $key => $question){
            foreach($tagInutile as $tag){
                if(in_array($tag, $question->getTags())){
                    unset($questionNiveauX[$key]);
                }
            }
        }

        return $questionNiveauX;

    }