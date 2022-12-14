<?php


// On inclut le fichier de configuration
include '../../BD/connexion_bd.php';

// Récupération des données
$id = $_GET['id'];
$tag = $_GET['tag'];

// Suppression du tag
$sql = "DELETE FROM lier_tag_depot WHERE depot_id = :id AND tag_id = :tag";
$stmt = $conn_bd->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->bindParam(':tag', $tag);
$stmt->execute();


header('Location: modifier.php?id='.$_GET['id']);