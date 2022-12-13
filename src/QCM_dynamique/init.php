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

    # récupération des questions avec des tags dans l'objet arbre
    $requete = "SELECT ID, LABEL, ETAT, TYPE FROM QUESTION
                WHERE ID IN (SELECT ID_QUESTION FROM composer WHERE ID_QCM = ".$_POST['id_qcm'].")";
    $stmt = $db->prepare($requete);
    $stmt->execute();
    $questionsUtiles = $stmt->fetchAll(); // tableau de questions (tableau associatif)

    # ajout des questions dans la banque de questions
    $banqueQuestions = array(); // tableau des questions de la bd (objet Question)
    foreach ($questionsUtiles as $question){
        $banqueQuestions[] = new Question($question['ID'], $question['LABEL'], $question['TYPE'], $question['ETAT']);
    }

    # ajout des réponses aux questions
    foreach ($banqueQuestions as $question){
        $stmt = $db->prepare("SELECT ID, LABEL, ETAT_VERITE FROM REPONSE WHERE ID_QUESTION = ".$question->getId());
        $stmt->execute();
        $reponses = $stmt->fetchAll(); // tableau de réponses (tableau associatif)
        foreach ($reponses as $reponse) {
            $question->addReponse(new Reponse($reponse['ID'], $reponse['LABEL'], $reponse['ETAT_VERITE']));
        }
    }

    # ajout des tags aux questions
    foreach ($banqueQuestions as $question){
        $stmt = $db->prepare("SELECT LABEL_TAG FROM lier_tag_question WHERE ID_QUESTION = ".$question->getId());
        $stmt->execute();
        $tags = $stmt->fetchAll(); // tableau de tags (tableau associatif)
        foreach ($tags as $tag) {
            $question->addTag($tag['LABEL_TAG']);
        }
    }

    $_SESSION['banqueQuestions'] = $banqueQuestions;