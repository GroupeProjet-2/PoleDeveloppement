<?php

    /*******************************************************************************
     * Ce fichier permet de consulter un dépot.
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

        header('Location: index.php');



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

    <link rel="stylesheet" href="../../../public/CSS/main.css">
    <link rel="stylesheet" href="../../../public/CSS/consulterDepot.css"/>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@300&display=swap" rel="stylesheet">

    <title>Consultation de ses dépôt</title>
</head>
<body>
    <?php
    include '../../header.php';
    include '../../sousHeader.php';
    ?>
    <main>
        <section class="creationDepot">
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
        </section>

        <section class="listeDepot">
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
        </section>

    </main>

    <?php
    include '../../footer.php';
    ?>
</body>
<script src="../../../public/JS/main.js"></script>


</html>