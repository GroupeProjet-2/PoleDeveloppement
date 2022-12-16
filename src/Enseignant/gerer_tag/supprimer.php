<?php

/*******************************************************************************

 * Ce fichier permet de supprimer un tag.

 *

 *******************************************************************************/

// Inclusion des fichiers nécessaires
include '../../BD/connexion_bd.php';

// Récupération des données
$label = $_GET['label'];

// Suppression du tag
$sql = "DELETE FROM Tag WHERE LABEL = :label";
$stmt = $conn_bd->prepare($sql);
$stmt->bindParam(':label', $label);
$stmt->execute();

header('Location: index.php');
