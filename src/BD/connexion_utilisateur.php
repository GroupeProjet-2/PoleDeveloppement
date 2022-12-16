<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="../../public/CSS/connexion_utilisateur.css">

    <title>Page de connexion</title>
</head>
    <body>
        <form method="post">
            <label for="login">Login</label>
            <input type="text" name="login" id="login" value="<?php echo $_POST['login'] ?? "" ?>">
            <br>
            <label for="password">Mot de passe</label>
            
            <div class="input-group f-row">
                <input type="password" name="password" id="password">
                <input type="button" onclick="voirMdp()" class="check_box" id="btnVisibility" style="display:none;">
                <label for="btnVisibility" id="visibilityImgContainer">
                </label>
            </div>
            
            <div class="input-group f-row r-allign">
                <input type="checkbox" name="remember" id="remember" value="1">
                <label for="remember">se rappeler de moi</label>
            </div>
            
            <br>
            <input type="submit" value="Se connecter">
        
        </form>
    </body>
</html>

<?php
    /**
     *
     * Connexion réussie:
     *   => $_SESSION['utilisateur'] = objet de la classe Utilisateur
     *   si 'se rappeler de moi' est coché:
     *     => $_COOKIE['utilisateur'] = objet de la classe Utilisateur
     *
     */
    $conn_bd = NULL;
    
    require_once('../Classes/Utilisateur.php');
    require_once('connexion_bd.php');
    
    if (isset($_POST['login']) && isset($_POST['password'])) {
        $login = $_POST['login'];
        $password = hash('sha256',$_POST['password']);
    
        // On verifie si l'utilisateur existe dans la base de données
        $query = $conn_bd->prepare("SELECT * FROM UTILISATEUR WHERE USER_LOGIN = '$login' OR USER_EMAIL = '$login' LIMIT 1");
        $query->execute();
        $result = $query->fetchAll();
    
        // Si l'utilisateur existe, on vérifie le mot de passe
        if (count($result) == 1) {
            // L'utilisateur existe
            if ($result[0]['USER_PASSWORD'] == $password) {
                // Le mot de passe est correct
    
                // On crée la session et on l'enregistre dedans
                session_start();
                
                $utilisateur = new Utilisateur($login);
    
                $_SESSION['utilisateur'] = $utilisateur;
    
                if(isset($_POST['remember'])) {
                    setcookie('utilisateur', $utilisateur, time() + 5*3600, null, null, false, true);
                }
    
                header('Location: ../index.php?id=' . $utilisateur->getRoleId());
            } else {
                // Le mot de passe est incorrect
                echo "Mot de passe incorrecte incorrect, réessayez.";
            }
        } else {
            // L'utilisateur n'existe pas
            echo 'Utilisateur inexistant, réessayez.';
        }
    }
?>

<script>
    // Fonction pour afficher / cacher le mot de passe
    const inputMdp = document.getElementById("password"); 
    const visibilityImgContainer = document.querySelector("#visibilityImgContainer");
   
    const imgShow = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">' +
            '<path fill="none" d="M0 0h24v24H0V0z"/>' +
            '<path fill="currentColor" d="M12 4C7 4 2.73 7.11 1 11.5 2.73 15.89 7 19 12 19s9.27-3.11 11-7.5C21.27 7.11 17 4 12 4zm0 12.5c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>' +
            '</svg>';
    const imgHide = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">' +
            '<path fill="none" d="M0 0h24v24H0V0zm0 0h24v24H0V0zm0 0h24v24H0V0zm0 0h24v24H0V0z"/>' +
            '<path fill="currentColor" d="M12 6.5c2.76 0 5 2.24 5 5 0 .51-.1 1-.24 1.46l3.06 3.06c1.39-1.23 2.49-2.77 3.18-4.53C21.27 7.11 17 4 12 4c-1.27 0-2.49.2-3.64.57l2.17 2.17c.47-.14.96-.24 1.47-.24zM2.71 3.16c-.39.39-.39 1.02 0 1.41l1.97 1.97C3.06 7.83 1.77 9.53 1 11.5 2.73 15.89 7 19 12 19c1.52 0 2.97-.3 4.31-.82l2.72 2.72c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41L4.13 3.16c-.39-.39-1.03-.39-1.42 0zM12 16.5c-2.76 0-5-2.24-5-5 0-.77.18-1.5.49-2.14l1.57 1.57c-.03.18-.06.37-.06.57 0 1.66 1.34 3 3 3 .2 0 .38-.03.57-.07L14.14 16c-.65.32-1.37.5-2.14.5zm2.97-5.33c-.15-1.4-1.25-2.49-2.64-2.64l2.64 2.64z"/>' +
            '</svg>';
    
    visibilityImgContainer.innerHTML = imgHide;
    
    function voirMdp() {
        inputMdp.type = inputMdp.type === "password" ? "text" : "password";
        visibilityImgContainer.innerHTML = inputMdp.type === "password" ? imgHide : imgShow;
    }
</script>

