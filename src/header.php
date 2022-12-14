<header>
    <h1>Auto-QCM</h1>

    <div class="right">
        <?php
            session_start();

            if (isset($_SESSION['user'])) {
                echo '<a href="logout.php" class="link">Mon Compte</a>';
            } else {
                echo '<a href="login.php" class="link">Me connecter</a>';
            }
        ?>
        <div id="themeSwitcherContainer">
            <button type="button" id="themeSwitcher"></button>

        </div>
    </div>

</header>
