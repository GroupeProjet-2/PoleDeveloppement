<?php

/*******************************************************************************
 * Ce fichier permet de consulter ses tag.
 *
 *******************************************************************************/

// Inclusion des fichiers nécessaires
include '../../BD/connexion_bd.php';

// Récupération des données
$login = "bruyere"; //!!! A remplacer par la variable de session


if (isset($_GET['Créer'])){
    $label = $_GET['label'];

    // Création du tag
    $sql = "INSERT INTO Tag (LABEL, CREATEUR) VALUES (:label, :createur)";
    $stmt = $conn_bd->prepare($sql);
    $stmt->bindParam(':label', $label);
    $stmt->bindParam(':createur', $login);
    $stmt->execute();

    header('Location: consulter.php');
}
?>

<html>
<head>
    <title>
        Gestions des tags
    </title>
</head>
<body>
<h1>VOS DEPOT</h1>
<table>
    <tr>
        <th>LABEL</th>
        <th>SUPPRIMER</th>
    </tr>
    <?php
    $sql = "SELECT * FROM TAG where CREATEUR = :login";
    $stmt = $conn_bd->prepare($sql);
    $stmt->bindParam(':login', $login);
    $stmt->execute();
    $result = $stmt->fetchAll();
    foreach ($result as $row) {
        echo "<tr>";
        echo "<td>" . $row['LABEL'] . "</td>";
        echo "<td><a href='supprimer.php?label=" . $row['LABEL'] . "'>Supprimer</a></td>";
        echo "</tr>";
    }


    ?>

</table>

<h1>CREER UN TAG</h1>
<form action="consulter.php" method="get">
    <label for="label">Label</label>
    <input type="text" name="label" id="label">
    <input type="submit" name="Créer" value="Créer">
</form>
</body>

</html>