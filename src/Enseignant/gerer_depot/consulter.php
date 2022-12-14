<?php

    /*******************************************************************************
     * Ce fichier permet de créer une question.
     *
     *******************************************************************************/

    // Inclusion des fichiers nécessaires
    include '../../BD/connexion_bd.php';

    // Récupération des données
    $login = "bruyere"; //!!! A remplacer par la variable de session

    if(isset($_GET['Créer'])){
        $titre = $_GET['titre'];
        $description = $_GET['description'];
        $date_ouverture = $_GET['dateOuverture'];
        $date_fermeture = $_GET['dateFermeture'];

        // Création du dépôt
        $sql = "INSERT INTO Depot (TITRE, DESCRIPTION, status, DATE_OUVERTURE, DATE_FERMETURE, CREATEUR) VALUES (:titre, :description, :status, :date_ouverture, :date_fermeture, :createur)";
        $stmt = $conn_bd->prepare($sql);
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':description', $description);
        $stmt->bindValue(':status', false);
        $stmt->bindParam(':date_ouverture', $date_ouverture);
        $stmt->bindParam(':date_fermeture', $date_fermeture);
        $stmt->bindParam(':createur', $login);
        $stmt->execute();

        header('Location: consulter.php');



    }

?>

<html>
<head>
    <title>
        Gestions des dépots
    </title>
</head>
<body>
    <h1>VOS DEPOT</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>TITRE</th>
            <th>DESCRIPTION</th>
            <th>STATUT</th>
            <th>DATE_OUVERTURE</th>
            <th>DATE_FERMETURE</th>
            <th>MODIFIER</th>
            <th>SUPPRIMER</th>
        </tr>
        <?php
            $sql = "SELECT * FROM DEPOT where CREATEUR = :login";
            $stmt = $conn_bd->prepare($sql);
            $stmt->bindParam(':login', $login);
            $stmt->execute();
            $result = $stmt->fetchAll();
            foreach ($result as $row) {
                echo "<tr>";
                echo "<td>" . $row['ID'] . "</td>";
                echo "<td>" . $row['TITRE'] . "</td>";
                echo "<td>" . $row['DESCRIPTION'] . "</td>";
                if ($row['status'] == null) {
                    echo "<td>Non ouvert</td>";
                } else {
                    echo "<td>Ouvert</td>";
                }
                echo "<td>" . $row['DATE_OUVERTURE'] . "</td>";
                echo "<td>" . $row['DATE_FERMETURE'] . "</td>";
                echo "<td><a href='modifier.php?id=" . $row['ID'] . "'>Modifier</a></td>";
                echo "<td><a href='supprimer.php?id=" . $row['ID'] . "'>Supprimer</a></td>";
                echo "</tr>";
            }


        ?>

    </table>

    <h1>Créer un dépôt</h1>
    <form>
        <label for="titre">Titre</label>
        <input type="text" name="titre" id="titre" required>
        <br>
        <label for="description">Description</label>
        <input type="text" name="description" id="description" required>
        <br>
        <label for="dateOuverture">Date d'ouverture</label>
        <input type="date" name="dateOuverture" id="dateOuverture" required>
        <br>
        <label for="dateFermeture">Date de fermeture</label>
        <input type="date" name="dateFermeture" id="dateFermeture" required>
        <br>
        <input type="submit" name="Créer" value="Créer">
    </form>
</body>

</html>