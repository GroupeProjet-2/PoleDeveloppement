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
    $_SESSION['questionsCorrectes'] = 0;

    # ouverture de la base de données
    $FICHIER_BD = "../BD/BD";
    $db = new PDO('sqlite:'.$FICHIER_BD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    # récupération de l'arbre
    $arbre_bd = $db->prepare("SELECT id FROM ARBRE WHERE id_qcm = ".POST['id_qcm']);
    $arbre_bd->execute();

    # remplissage de l'objet arbre
    $requete = "SELECT niveau.valeur, niveau.label_tag FROM niveau WHERE niveau.id_arbre = ".$arbre_bd['id'] . " ORDER BY niveau.valeur";
    $stmt = $db->prepare($requete);
    $stmt->execute();
    $niveaux = $stmt->fetchAll();

    $arbre = new Arbre($arbre_bd['id']);
    foreach ($niveaux as $niveau){
        $arbre->addNiveau($niveau['valeur']);
        $arbre->addTag($niveau['valeur'], $niveau['label_tag']);
    }

    $_SESSION['arbre'] = $arbre;

    # récupération des questions
