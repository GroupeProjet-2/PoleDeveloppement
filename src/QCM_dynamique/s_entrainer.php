<!doctype html>
<html lang="fr">
<head>
    <title>QCM Dynamique</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">


    <meta charset="utf-8">
    <meta name="authors" content="Mathis, Hériveau, Tom Montbord, Tom Planche">
    <meta name="description" content="Proof Of Concept - SAE_3 Pole Développement">
    <meta name="viewport" content="width=device-width, height=device-height ,initial-scale=1.0">

    <link rel="stylesheet" href="../../public/CSS/main.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@300&display=swap" rel="stylesheet">
</head>
<body>
    <?php
        include '../header.php';
    ?>



    <?php

    /**************************************************************************
     *                                                                        *
     *                          Chercher une question                         *
     *                                                                        *
     *************************************************************************/

    // Chercher la liste des questions utilisables
    if($_SESSION['changementNiveau']){
        $_SESSION['changementNiveau'] = false;
        include_once "fonctions/filtrerParNiveau.php";
        $_SESSION['questionNiveauX'] = questionNiveauX($_SESSION['niveauActuel'], $_SESSION['arbre'], $_SESSION['banqueQ']);
    }

    include_once "fonctions/filtrerParStatut.php";
    $questionUtilisable = questionUtilisable($_SESSION['questionNiveauX'], $_SESSION['arbre'], $_SESSION['banqueQ']);

    if($_SESSION['dejaVu'] != 0){
        $questionUtilisable = array_diff($questionUtilisable, $_SESSION['dejaVu']);
    }

    // Si il n'y a plus de question utilisable, on passe au niveau suivant
    //---------------------------

    // Chosisir une question au hasard
    $_SESSION['questionActuelle'] = array_rand($questionUtilisable, 1);

    // Ajouter la question à la liste des questions déjà vu
    $_SESSION['dejaVu'][] = $_SESSION['questionActuelle'];
    // Si la liste des questions déjà vu est plus grande que 5, on la réduit à 5
    if(count($_SESSION['dejaVu']) > 5){
        $_SESSION['dejaVu'] = array_slice($_SESSION['dejaVu'], -5);
    }

    ?>

    <!--
    *****************************************************************************************
    *                                                                                       *
    *                                      Afficher la question                             *
    *                                                                                       *
    ******************************************************************************************
    -->

    <div class="container">
        <div class="question">
            <h1><?php echo $_SESSION['questionActuelle']->getQuestion(); ?></h1>
        </div>
        <div class="reponses">
            <form action="s_entrainer.php" method="post">
                <?php
                    $reponses = $_SESSION['banqueQ'][$_SESSION['questionActuelle']]->getReponses();
                    foreach ($reponses as $reponse){
                        echo "<input type='radio' name='reponse' value='".$reponse->getId()."'>".$reponse->getReponse()."<br>";
                    }
                ?>
                <input type="submit" value="Valider">
            </form>
        </div>
    </div>

    <?php
        include '../footer.php';
    ?>




</body>
</html>



