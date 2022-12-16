<?php
    session_start();

    // On vérifie que l'utilisateur(classe) stocké dans ($_SESSION['utilisateur']) est un enseignant

    $role = $_GET['id']; // A changer pour le role de l'utilisateur


    // On vérifie que l'utilisateur est connecté et qu'il est un enseignant
    if(isset($_SESSION['utilisateur'])) {
    } else if(isset($_COOKIE['utilisateur'])) {
        $_SESSION['utilisateur'] = $_COOKIE['utilisateur'];
    } else {
        header('Location: ../index.php');
    }



    if($role == 1) {
        $role = "Etudiant";
        header('Location: Etudiant/index.php');
    } else if ($role == 2) {
        $role = "Enseignant";
        header("Location: Enseignant/index.php");
    } else if ($role == 3) {
        $role = "Administrateur";
        header("Location: Admin/index.php");
    } else {
        //header("Location: ../index.php");
    }
