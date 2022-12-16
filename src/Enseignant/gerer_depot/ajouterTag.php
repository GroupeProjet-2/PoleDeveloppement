<?php

    // On inclut le fichier de configuration
    include '../../BD/connexion_bd.php';

    // Récupération des données
    $id = $_GET['id'];
    $tag = $_GET['tag'];
    if($_GET['tag']=='none'){


    }else{
        // Ajout du tag
        $sql = "INSERT INTO lier_tag_depot (depot_id, tag_id) VALUES (:id, :tag)";
        $stmt = $conn_bd->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':tag', $tag);
        $stmt->execute();

    }

    header('Location: modifier.php?id='.$id);

