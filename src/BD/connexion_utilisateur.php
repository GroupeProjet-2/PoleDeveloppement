<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Page de connexion</title>
</head>
<body>
<form method="post">
    <label for="login">Login</label>
    <input type="text" name="login" id="login" value="<?php echo isset($_POST['login']) ? $_POST['login'] : "" ?>">
    <br>
    <label for="password">Mot de passe</label>
    <input type="password" name="password" id="password">
    <input type="checkbox" onclick="voirMdp()" class="check_box" id="checkboxMdp"> <label for="checkboxMdp"></label> <br>
    <input type="checkbox" name="remember" id="remember" value="1">
    <label for="checkboxMdp">se rappeler de moi</label>
    <br>
    <input type="submit" value="Se connecter">

</form>
</body>
</html>

<?php
/**
 * Pour cette page, on a besoin de la connexion à la base de données.
 *
 * On passe aussi en GET le role de l'utilisateur (1 pour un étudiant, 2 pour un enseignant et 3 pour un admin).
 */
$conn_bd = NULL;

require_once('../Classes/Utilisateur.php');
require_once('connexion_bd.php');
if (isset($_POST['login']) && isset($_POST['password'])) {
    $login = $_POST['login'];
    $password = hash('sha256',$_POST['password']);

    // On verifie si l'utilisateur existe dans la base de données
    $query = $conn_bd->prepare("SELECT * FROM UTILISATEUR WHERE USER_LOGIN = '{$login}' OR USER_EMAIL = '{$login}' LIMIT 1");
    $query->execute();
    $result = $query->fetchAll();

    // Si l'utilisateur existe, on vérifie le mot de passe
    if (count($result) == 1) {
        // L'utilisateur existe
        if ($result[0]['USER_PASSWORD'] == $password) {
            // Le mot de passe est correct

            // On crée la session et on l'enregistre dedans
            session_start();
            $_SESSION['utilisateur'] = new Utilisateur($login);
            if(isset($_POST['remember'])) {
                setcookie('utilisateur', new Utilisateur($login), time() + 5*3600, null, null, false, true);
            }

            header('Location: ../../index.php');

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
    inputMdp = document.getElementById("password"); // input du mot de passe
    function voirMdp(){
        inputMdp.type === "password" ? inputMdp.type = "text" : inputMdp.type = "password";
    }
</script>

<style>
    .check_box {
        display:none;
    }

    .check_box + label{
        background:url('../../public/images/eye-slash-solid.svg') no-repeat;
        height: 16px;
        width: 16px;
        display:inline-block;
    }

    .check_box:checked + label{
        background:url('../../public/images/eye-solid.svg') no-repeat;
    }
</style>


