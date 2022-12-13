<?php

    /**
     * Ce fichier permet de se connecter à la base de données.
     * Il sera inclus dans les fichiers qui ont besoin de se connecter à la base de données et
     * paragetra donc la variable $conn_bd.
     */

    try {
        $conn_bd = new PDO('sqlite:'.dirname(__FILE__).'/BD');
        $conn_bd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $conn_bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ERRMODE_WARNING | ERRMODE_EXCEPTION | ERRMODE_SILENT
    } catch(Exception $e) {
        echo "Impossible d'accéder à la base de données SQLite : " . $e->getMessage();
        die();
    }

    /**
     * testBD
     *
     * Teste la connexion à la base de données.
     *
     * @return void
     */
    function testBD(): void {
        global $conn_bd;

        $sql = "SELECT * FROM Utilisateur";
        $stmt = $conn_bd->prepare($sql);

        $stmt->execute();

        $result = $stmt->fetchAll();

        var_dump($result);

    }


