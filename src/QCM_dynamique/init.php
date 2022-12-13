<?php

    // Afficher les erreurs variable de session

    # include les classes
    include_once("../CLASSES/Arbre.php");
    include_once("../CLASSES/Question.php");
    include_once("../CLASSES/Reponse.php");
    include_once("../CLASSES/Difficulte.php");
    include_once("../CLASSES/Type.php");
    include_once("../CLASSES/Statut.php");

    session_start();
echo str_dump($_SESSION);

    # création des variables de session simples
    $_SESSION['niveauActuel'] = 0;
    $_SESSION['changementNiveau'] = false;
    $_SESSION['questionsEffectuees'] = 0;
    $_SESSION['questionsReussies'] = 0;

    # ouverture de la base de données
    $FICHIER_BD = "../BD/BD";
    $db = new PDO('sqlite:'.$FICHIER_BD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    # remplissage de l'objet arbre
    $requete = "SELECT niveau.VALEUR, niveau.LABEL_TAG FROM niveau WHERE niveau.ID_QCM = ".$_POST['id_qcm'] . " ORDER BY niveau.VALEUR";
    $stmt = $db->prepare($requete);
    $stmt->execute();
    $niveaux = $stmt->fetchAll();

    $arbre = new Arbre($_POST['id_qcm']);
    foreach ($niveaux as $niveau){
        $arbre->addNiveau($niveau['VALEUR']);
        $arbre->addTag($niveau['VALEUR'], $niveau['LABEL_TAG']);
    }

    $_SESSION['arbre'] = $arbre;
