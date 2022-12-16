<?php

    /*******************************************************************************
     * Ce fichier permet de créer une question.
     *
     *******************************************************************************/

    // Inclusion des fichiers nécessaires
    include '../BD/connexion_bd.php';

    // Récupération des données
    $login = "bruyere"; //!!! A remplacer par la variable de session
    $id_depot = 1;      //!!! A remplacer par la variable de session


    if (isset($_GET['Valider'])){
        $question = $_GET['Question'];
        $difficulte = $_GET['Difficulté'];
        $type = $_GET['Type'];
        $tag = $_GET['Tag'];
        $reponses = $_GET['Réponses'];
        $bonneReponse = $_GET['bonneReponse'];


        // Création de la question
        $sql = "INSERT INTO Question (LABEL, ETAT, TYPE, ID_UTILISATEUR, DIFFICULTE, ID_DEPOT) VALUES (:label, :etat, :type, :id_utilisateur, :difficulte, :id_depot)";
        $stmt = $conn_bd->prepare($sql);
        $stmt->bindParam(':label', $question);
        $stmt->bindValue(':etat', 'A_VERIFIER');
        $stmt->bindParam(':type', $type);
        $stmt->bindValue(':id_utilisateur', $login);
        $stmt->bindParam(':difficulte', $difficulte);
        $stmt->bindValue(':id_depot', $id_depot);
        $stmt->execute();


        // Récupération de l'id de la question
        $id_question = $conn_bd->lastInsertId();

        // Création des réponses
        $i = 1;
        foreach ($reponses as $reponse) {
            if ($reponse != "") {
                $sql = "INSERT INTO Reponse (LABEL, ETAT_VERITE, QUESTION_ID) VALUES (:label, :etat_verite, :question_id)";
                $stmt = $conn_bd->prepare($sql);
                $stmt->bindParam(':label', $reponse);
                $etat = "FAUX";
                foreach ($bonneReponse as $bonne) {
                    if ($i == (int)$bonne) {
                        $etat = "VRAI";
                    }
                }
                $stmt->bindParam(':etat_verite', $etat);
                $stmt->bindParam(':question_id', $id_question);
                $stmt->execute();
            }
            $i++;
        }


        // Création du lien entre la question et le tag
        $sql = "INSERT INTO lier_tag_question (LABEL_TAG, ID_QUESTION) VALUES (:label_tag, :id_question)";
        $stmt = $conn_bd->prepare($sql);
        $stmt->bindParam(':label_tag', $tag);
        $stmt->bindParam(':id_question', $id_question);
        $stmt->execute();
    }
?>


<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">


    <meta charset="utf-8">
    <meta name="authors" content="Mathis, Hériveau, Tom Montbord, Tom Planche">
    <meta name="description" content="Proof Of Concept - SAE_3 Pole Développement">
    <meta name="viewport" content="width=device-width, height=device-height ,initial-scale=1.0">

    <link rel="stylesheet" href="/public/CSS/main.css">
    <link rel="stylesheet" href="../../public/CSS/creerQuestion.css"/>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@300&display=swap" rel="stylesheet">

    <title>Creation de question</title>
</head>
<body>
    <?php
       include '../header.php';
       include '../sousHeader.php';
    ?>

    <main>
        <section class="container">
            <form>
                <!-- ID de la question auto Incrementé -->
                <!-- Question -->
                <div class="group-form question">
                    <label for="Titre">Votre question : </label>
                    <input type="text" name="Question" id="Question" required>
                </div>

                <!-- Etat prédéfini -->
                <!-- Difficulté -->
                <div class="group-form difficulte">
                    <label for="Difficulté">Difficulté : </label>
                    <select name="Difficulté" id="Difficulté">
                        <option value="facile">Facile</option>
                        <option value="moyen">Moyen</option>
                        <option value="difficile">Difficile</option>
                    </select>
                </div>
                <!-- Type de question -->
                <div class="group-form type">
                    <label for="Type">Type : </label>
                    <?php
                        $sql = "SELECT * FROM type";
                        $stmt = $conn_bd->prepare($sql);
                        $stmt->execute();
                        $result = $stmt->fetchAll();

                        echo "<select name='Type' id='Type'>";
                        foreach ($result as $row) {
                            echo "<option value='".$row['LABEL']."'>".$row['LABEL']."</option>";
                        }
                        echo "</select>";
                    ?>
                </div>
                <!-- Tag -->
                <div class="group-form tag">

                    <label for="Tag">Ajouter des tags :</label>
                    <?php
                    $sql = "SELECT * FROM tag";
                    $stmt = $conn_bd->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->fetchAll();

                    echo "<select name='Tag' id='Tag' >";
                    foreach ($result as $row) {
                        echo "<option value='".$row['LABEL']."'>".$row['LABEL']."</option>";
                    }
                    echo "</select>";

                    ?>
                </div>
                <!-- Liste des tags -->
                <div class="group-form liste-tag">
                    <label for="ListeTag">Liste des tags :</label>
                    <ul id="ListeTag" required>
                        <li>Mathématique</li>
                        <li>Physique</li>
                        <li>Physique</li>
                        <li>Physique</li>
                        <li>Physique</li>
                    </ul>
                </div>
                <!-- Réponses -->
                <div class="group-form listeReponses">
                    <div class="reponses">
                    <input type="radio" class="lesReponses" name="bonneReponse[]" value="1">
                    <input type="text"  class="valeurReponse" name="Réponses[]" id="Réponses" required>
                    <input type="radio" class="lesReponses" name="bonneReponse[]" value="2">
                    <input type="text"  class="valeurReponse" name="Réponses[]" id="Réponses" required>
                    <input type="radio" class="lesReponses" name="bonneReponse[]" value="3">
                    <input type="text"  class="valeurReponse" name="Réponses[]" id="Réponses">
                    <input type="radio" class="lesReponses" name="bonneReponse[]" value="4">
                    <input type="text"  class="valeurReponse" name="Réponses[]" id="Réponses">
                </div>
                </div>
                <!-- Bouton de validation -->
                <div class="bouton">
                    <input type="submit" name="Valider" value="Valider">
                </div>
            </form>
        </section>
    </main>

    <?php
       include '../footer.php';
    ?>
</body>

<script src="/public/JS/main.js"></script>

<script>
    // Script qui change le type d'input en fonction du type de question selectionné
    let type = document.getElementById("Type");

    type.addEventListener("change", function() {
        let type = document.getElementById("Type").value;

        let lesReponses = document.getElementsByClassName("lesReponses");
        let Réponses = document.getElementsByClassName("valeurReponse");

        for (let i = 0; i < lesReponses.length; i++) {
            Réponses[i].type = "text";
            Réponses[i].value = "";
            Réponses[i].readOnly = false;
        }

        if (type == "Choix multiple") {
            for (let i = 0; i < lesReponses.length; i++) {
                lesReponses[i].type = "checkbox";
            }
        } else if (type == "Choix unique") {
            for (let i = 0; i < lesReponses.length; i++) {
                lesReponses[i].type = "radio";
            }
        }
        else if (type == "Vrai ou faux") {
            // Mettre un bouton radio pour vrai et un pour faux
            // Mettre un input caché pour les autres réponses
            for (let i = 0; i < lesReponses.length; i++) {
                if (i == 0) {
                    lesReponses[i].type = "radio";
                    Réponses[i].value = "Vrai";
                    Réponses[i].readOnly = true;
                } else if (i == 1) {
                    lesReponses[i].type = "radio";
                    Réponses[i].value = "Vrai";
                    Réponses[i].value = "Faux";
                    Réponses[i].readOnly = true;
                } else {
                    lesReponses[i].type = "hidden";
                    Réponses[i].type = "hidden";
                }
            }
        }

    });
</script>
</html>