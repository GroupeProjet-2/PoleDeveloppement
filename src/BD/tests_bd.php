<?php

    $db = new PDO('sqlite: ./BD');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//    $login_recherche = 'tplanche001';
//    $sql = "SELECT * FROM USER WHERE USER_LOGIN = :login";
//    $stmt = $db->prepare($sql);
//    $stmt->bindParam(':login', $login_recherche);
//    $stmt->execute();
//
//    $result = $stmt->fetch();
//
//    if ($result) {
//        echo "Login trouvé";
//    } else {
//        echo "Login non trouvé";
//    }

    function insertUser(
        $login,
        $firstName,
        $lastName,
        $mail,
        $password,
        $role_id,
        $td = null,
        $tp = null
    ): bool {
        global $db;

        $password = hash('sha256', $password);

        $sql = "INSERT INTO UTILISATEUR (
            USER_LOGIN,
            USER_FIRST_NAME,
            USER_LAST_NAME,
            USER_EMAIL,
            USER_PASSWORD,
            USER_ROLE_ID
        ) VALUES (
            :login,
            :firstName,
            :lastName,
            :mail,
            :password,
            :role_id
        )";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':mail', $mail);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role_id', $role_id);

        $stmt->execute();

        $error = $stmt->errorInfo();
        if ($error[0] !== '00000') {
            echo "Erreur lors de l'insertion de l'utilisateur";
            echo $error[2];
            return false;
        } else {
            echo "Insertion de l'utilisateur réussie";
        }

        if ($role_id == 1) {
            // C'est un étudiant
            $sql = "INSERT INTO ETUDIANT (ETUDIANT_LOGIN, TD, TP) VALUES (
                :login,
                :td,
                :tp
            )";

            $stmt = $db->prepare($sql);
            $stmt->bindParam(':login', $login);
            $stmt->bindParam(':td', $td);
            $stmt->bindParam(':tp', $tp);

            $stmt->execute();

            $error = $stmt->errorInfo();
            if ($error[0] !== '00000') {
                echo "Erreur lors de l'insertion de l'étudiant";
                echo $error[2];
                return false;
            } else {
                echo "Insertion de l'étudiant réussie";
                return true;
            }
        }

        return true;
    }


