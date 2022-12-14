<?php


    /**
     * Pour cette page, on a besoin de la connexion à la base de données.
     *
     * On passe aussi en GET le role de l'utilisateur (1 pour un étudiant, 2 pour un enseignant et 3 pour un admin).
     */

    $role = $_GET['role'] ?? 1;

    require_once dirname(__FILE__).'/../BD/connexion_bd.php';
?>



