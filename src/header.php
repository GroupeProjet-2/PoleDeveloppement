
<?php
    $current_page = $_GET['page'] ?? 'accueil';
?>

<header>
    <div class="header-group">
        <h1>Auto-QCM</h1>

        <div>
            <ol class="tabs">
                <li><a href="#" class="link <?php echo $current_page == 'accueil' ? 'active"' : '' ?>">Accueil</a></li>

                <?php
                /**
                 * ! A Changer - Enlever les '!' quand connexion faite
                 */
                if (!isset($_SESSION['type'])) {
//                    if ($_SESSION['type'] == 'etudiant') {
                    if (1+1 == 2) {
                        echo '<li><a href="./src/etudiant/etudiant.php" class="link ' . ($current_page == 'role' ? 'active' : '') . '">Etudiant</a></li>';
                    } else if ($_COOKIE['type'] == 'professeur') {
                        echo '<li><a href="./src/professeur/professeur.php" class="link ' . ($current_page == 'role' ? 'active' : '') . '">Professeur</a></li>';
                    }
                }

                ?>

                <li><a href="#" class="link">Contact</a></li>
            </ol>
        </div>

    </div>

    <div class="header-group">
        <?php
            session_start();

            if (isset($_SESSION['user'])) {
                echo '<button class="btn btn-primary" onclick="window.location.href=\'./src/connexion/deconnexion.php\'">Déconnexion</button>';
            } else {
                echo '<button class="btn btn-primary" onclick="window.location.href=\'./src/connexion/connexion.php\'">Connexion</button>';
            }
        ?>
        <div id="themeSwitcherContainer">
            <button type="button" id="themeSwitcher"></button>
        </div>
    </div>

</header>
