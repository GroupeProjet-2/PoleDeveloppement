
<?php

    /**************************************************************************
     *                                                                        *
     *  Ce programme permet d'ajout sous le header la lignée de path          *
     *  pour savoir où on se trouve                                           *
     *                                                                        *
     **************************************************************************/

    // Stratégie : on récupère le path de la page actuelle, on le coupe en morceaux
    // et on affiche les morceaux un par un en les liant à la page correspondante.

    // On récupère le path de la page actuelle
    $path = $_SERVER['PHP_SELF'];

    // On coupe le path en morceaux
    $path = explode('/', $path);

    echo '<div class="path">';
    echo '<ul>';

    // On affiche les morceaux un par un
    for ($i = 2; $i < count($path); $i++) {
        // On récupère le nom du fichier
        $nom_fichier = explode('.', $path[$i])[0];

        // On récupère le nom de la page
        $nom_page = ucfirst($nom_fichier);
        if ($nom_page == 'Src') {
            $nom_page = 'Accueil';
        }

        // On affiche le lien
        $apath = '<a href="';
        for ($j = count($path) - 1; $j > $i; $j--) {
            $apath .= "../";
        }
        // Si le nom du fichier est Etudiant
        if ($nom_fichier == "Etudiant") {
            $apath .= '?id=1">' . $nom_page . '</a>';
        }
        // Si le nom du fichier est Enseignant
        elseif ($nom_fichier == "Enseignant") {
            $apath .= '?id=2">' . $nom_page . '</a>';
        }
        // Si le nom du fichier est Admin
        elseif ($nom_fichier == "Admin") {
            $apath .= '?id=3">' . $nom_page . '</a>';
        }
        // Si le nom du fichier est index
        else{
            $apath .= '">' . $nom_page . '</a>';
        }

        echo '<li>' . $apath . '</li>';


    }
    echo '</ul>';
    echo '</div>';

