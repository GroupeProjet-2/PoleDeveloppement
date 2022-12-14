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

    <link rel="stylesheet" href="public/CSS/main.css">
    <link rel="stylesheet/less" type="text/css" href="public/CSS/index.scss"/>
    <script src="https://cdn.jsdelivr.net/npm/less@4.1.1"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@300&display=swap" rel="stylesheet">

    <title>Titre</title>
</head>

<body>
<noscript>You need to enable JavaScript to run this app.</noscript>

<?php
include('./src/header.php')
?>

<main>

    <h1>Application web responsive de gestion de QCM d’enseignement</h1>

    <p>Cette application a été développée dans le cadre du projet de fin d’études de la formation d'un B.U.T informatique <br>
        SAE S3.01 - Développement d’application et Gestion de projet <br>
        <br>
        Cette application permet au professeur de créer des QCMs et de les partager avec ses étudiants. <br></p>
    <!-- G E R E R   E T U D I A N T -->
    <div class="layout-Horizontal">
        <div class="choixDeLutilisateur">
            <svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="max-width" height="auto" viewBox="0 0 1280.000000 1248.000000"preserveAspectRatio="xMidYMid meet">
                <metadata>
                    Created by potrace 1.15, written by Peter Selinger 2001-2017
                </metadata>
                <g transform="translate(0.000000,1248.000000) scale(0.100000,-0.100000)"
                   fill="#000000" stroke="none">
                    <path d="M6005 12474 c-22 -2 -89 -9 -149 -14 -542 -53 -1087 -253 -1556 -571 -1089 -738 -1659 -2029 -1470 -3331 117 -802 507 -1518 1129 -2075 454 -406
    1057 -697 1666 -802 220 -39 317 -46 595 -46 278 0 375 7 595 46 1218 211 2250 1096 2647 2269 353 1043 189 2180 -442 3075 -568 804 -1437 1318 -2425
    1435 -92 11 -518 21 -590 14z"/> <path d="M8585 5886 c-478 -363 -1085 -626 -1687 -730 -247 -43 -348 -51 -678 -51 -319 0 -364 3 -612 41 -586 88 -1171 326 -1668 676 l-114 81 -106 -17
    c-58 -10 -181 -38 -275 -61 -807 -204 -1528 -616 -2120 -1210 -726 -728 -1170 -1655 -1297 -2704 -29 -238 -32 -741 -5 -930 53 -378 142 -589 320 -758 171
    -163 421 -234 772 -220 269 11 533 55 1230 208 817 179 1195 244 1690 291 205 19 4525 19 4730 0 495 -47 873 -112 1690 -291 697 -153 961 -197 1230 -208
    254 -10 438 21 605 103 172 84 315 254 390 463 74 203 108 420 117 736 34 1230 -451 2439 -1332 3320 -707 707 -1608 1156 -2597 1294 -206 29 -201 30
    -283 -33z"/> </g></svg>
            <label>SE CONNECTER</label>
            <!-- Bouton qui envoie sur la page asset/php/connexion.php avec le type etudiant, onclick : envoyer sur connexion.php et mettre le cookie type-->
            <button onclick="document.location.href='./src/BD/connexion_utilisateur.php'">Connexion</button>
        </div>

    </div>
</main>

<footer>

    <h1>Footer</h1>

</footer>

</body>

<script src="public/JS/main.js"></script>

</html>
