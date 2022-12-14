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

        echo "Question créée <br>";

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
                echo "Réponse : $reponse <br>";
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


<html>
<head>
    <title>
        Création de question
    </title>
</head>
<body>
    <h1>Création de question</h1>
    <form>
        <!-- ID de la question auto Incrementé -->
        <!-- Question -->
        <label for="Titre">Question</label>
        <input type="text" name="Question" id="Question">
        <!-- Etat prédéfini -->
        <!-- Difficulté -->
        <label for="Difficulté">Difficulté</label>
        <select name="Difficulté" id="Difficulté">
            <option value="facile">Facile</option>
            <option value="moyen">Moyen</option>
            <option value="difficile">Difficile</option>
        </select>
        <!-- Type de question -->
        <label for="Type">Type de question</label>
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
        <!-- Réponses -->
        <label for="Réponses">Vos réponses :</label>
        <input type="checkbox" name="bonneReponse[]" value="1">
        <input type="text" name="Réponses[]" id="Réponses">
        <input type="checkbox" name="bonneReponse[]" value="2">
        <input type="text" name="Réponses[]" id="Réponses">
        <input type="checkbox" name="bonneReponse[]" value="3">
        <input type="text" name="Réponses[]" id="Réponses">
        <input type="checkbox" name="bonneReponse[]" value="4">
        <input type="text" name="Réponses[]" id="Réponses">
        <!-- Tag -->
        <label for="Tag">Tag</label>
        <?php
        $sql = "SELECT * FROM tag";
        $stmt = $conn_bd->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

        echo "<select name='Tag' id='Tag'>";
        foreach ($result as $row) {
            echo "<option value='".$row['LABEL']."'>".$row['LABEL']."</option>";
        }
        echo "</select>";

        ?>
        <!-- Bouton de validation -->
        <input type="submit" name="Valider" value="Valider">



    </form>
</body>
</html>