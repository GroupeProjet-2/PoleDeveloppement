<?php
    // Connexion à la base de données
    include '../../BD/connexion_bd.php';
    // Suppression du dépôt
    $sql = "DELETE FROM DEPOT WHERE ID = :id";
    $stmt = $conn_bd->prepare($sql);
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    // Redirection vers la page de gestion des dépôts
    header('Location: consulter.php');
?>