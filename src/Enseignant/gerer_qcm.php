<?php

    /**
     * Ce fichier permet à un enseignant de gérer un de ses QCM.
     * Pour accéder à cette page, l'utilisateur doit être connecté et être un enseignant.
     * Il doit également avoir cliqué sur un QCM dans la liste des QCM de l'enseignant afin
     * d'avoir le get id_qcm.
     */

    // Vérification nécessaire pour accéder à cette page.
    if (!(
        isset($_SESSION['login']) && // l'utilisateur est connecté
        isset($_SESSION['type']) && // la variable de session 'type' existe
        $_SESSION['type'] == 'enseignant' // l'utilisateur est un enseignant
        && isset($_GET['id_qcm']) // l'identifiant du QCM est passé en GET
    )) {
//        header('Location: index.php');

        require_once("../BD/connexion_bd.php");

        // Si un post est envoyé, on modifie la base de données
        if (isset($_POST['titre'])) {
            modifier_informations_qcm();
            $id_qcm = $_POST['id_qcm'];
        } else {
            $id_qcm = $_GET['id_qcm'];
        }

        $qcm = recuperer_informations_qcm($id_qcm);

//        exit();
    }

    /**
     * Récupération des informations du QCM.
     *
     * @param string $id_qcm L'identifiant du QCM.
     * @return array Les informations du QCM => array('titre' => ..., 'description' => ..., 'est_public' => ...)
     */
    function recuperer_informations_qcm(string $id_qcm): array {
        global $conn_bd;

        $req = $conn_bd->prepare('SELECT TITRE, DESCRIPTION, EST_PUBLIC FROM QCM WHERE ID = ?');
        $req->execute(array($id_qcm));

        $reponse = $req->fetch();

        return array(
            'titre' => $reponse['TITRE'],
            'description' => $reponse['DESCRIPTION'],
            'est_public' => $reponse['EST_PUBLIC']
        );
    }


    function modifier_informations_qcm(): bool {
        global $conn_bd;

        $id_qcm = $_POST['id_qcm'];
        $titre = $_POST['titre'];
        $description = $_POST['description'];

        $est_public = $_POST['est_public'] ?? 0;

        $req = $conn_bd->prepare('UPDATE QCM SET TITRE = :nvTitre, DESCRIPTION = :nvDescription, EST_PUBLIC = :nvEstPublic WHERE ID = :idQcm');

        $req->bindParam(':nvTitre', $titre);
        $req->bindParam(':nvDescription', $description);
        $req->bindParam(':nvEstPublic', $est_public);
        $req->bindParam(':idQcm', $id_qcm);

        return $req->execute();
    }

?>


<!doctype html>
<html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
	    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">


        <meta charset="utf-8">
        <meta name="authors" content="Mathis, Hériveau, Tom Montbord, Tom Planche">
		<meta name="description" content="Proof Of Concept - SAE_3 Pole Développement">
	    <meta name="viewport" content="width=device-width, height=device-height ,initial-scale=1.0">

        <link rel="stylesheet" href="/public/CSS/main.css">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@300&display=swap" rel="stylesheet">

        <title>Gérer mon QCM</title>
    </head>

    <body>
        <noscript>You need to enable JavaScript to run this app.</noscript>

        <?php
            require_once('../header.php');
        ?>

        <main>

            <?php
                echo "<h1 class='titre'>Gérer mon QCM - " . $qcm['titre'] . " -</h1>";
            ?>

            <form method="post" class="infos-qcm">

                <input type="hidden" name="id_qcm" value="<?php echo $id_qcm; ?>" hidden>

                <div class="labelInputRow">
                    <label for="titre">Titre du QCM</label>
                    <?php
                        echo "<input type=\"text\" name=\"titre\" id=\"titre\" value=\"" . $qcm['titre'] . "\">";
                    ?>
                </div>

                <label for="description">Description du QCM</label>
                <?php
                    echo "<textarea name=\"description\" id=\"description\" cols=\"60\" rows=\"5\">" . $qcm['description'] . "</textarea>";
                ?>

                <div class="labelInputRow">
                    <label for="est-public">Visible par les étudiants</label>
                    <?php
                        echo "<input type=\"checkbox\" onclick=\"toggleCheckbox(this)\" name=\"est_public\" id=\"est_public\" " . ($qcm['est_public'] ? 'checked' : '') . ">";
                    ?>
                </div>

                <input type="submit" value="Enregistrer les modifications">
            </form>
        </main>

        <footer>

	        <h1>Footer</h1>

        </footer>

    </body>

    <script src="/public/JS/main.js"></script>

</html>

