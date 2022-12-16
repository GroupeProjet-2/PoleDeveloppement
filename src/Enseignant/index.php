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
    <link rel="stylesheet" href="../../public/CSS/index.css"/>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@300&display=swap" rel="stylesheet">

    <title>Creation de question</title>
</head>

<body>
<noscript>You need to enable JavaScript to run this app.</noscript>

<?php
include '../header.php';
include '../sousHeader.php';
?>

<main>

    <section class="container">
        <div class="container__title">
            <h1>Vous etes un Enseignant</h1>
        </div>

        <button class="container__button" onclick="window.location.href = './gerer_qcm.php?id_qcm=1';">Gerer ses QCM</button>
        <button class="container__button" onclick="window.location.href = './gerer_tag/consulter.php';">Gerer ses tag</button>
        <button class="container__button" onclick="window.location.href = './gerer_depot';">Gerer ses dépot</button>

    </section>
</main>

<footer>

    <h1>Footer</h1>

</footer>

</body>

<script src="public/JS/main.js"></script>

</html>
