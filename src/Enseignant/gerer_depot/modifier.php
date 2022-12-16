<?php
    /*******************************************************************************
     * Ce fichier permet de modifier un dépôt.
     *
     *******************************************************************************/

    // On inclut le fichier de configuration
    include '../../BD/connexion_bd.php';

    // On récupère les données
    $id = $_GET['id'];

    // On récupère les données du dépôt
    $sql = "SELECT * FROM DEPOT WHERE ID = :id";
    $stmt = $conn_bd->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $depot = $stmt->fetch();
    $titre = $depot['TITRE'];
    $description = $depot['DESCRIPTION'];
    $status = $depot['status'];
    $date_ouverture = $depot['DATE_OUVERTURE'];
    $date_fermeture = $depot['DATE_FERMETURE'];

    if(isset($_GET['Modifier'])){
        $titre = $_GET['titre'];
        $description = $_GET['description'];
        if(isset($_GET['status'])){
            $status = true;
        }else{
            $status = false;
        }

        $date_ouverture = $_GET['dateOuverture'];
        $date_fermeture = $_GET['dateFermeture'];

        // Modification du dépôt
        $sql = "UPDATE Depot SET TITRE = :titre, DESCRIPTION = :description, status = :STATUT, DATE_OUVERTURE = :date_ouverture, DATE_FERMETURE = :date_fermeture WHERE ID = :id";
        $stmt = $conn_bd->prepare($sql);
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':STATUT', $status);
        $stmt->bindParam(':date_ouverture', $date_ouverture);
        $stmt->bindParam(':date_fermeture', $date_fermeture);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        header('Location: index.php');

    }


?>

<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">


    <meta charset="utf-8">
    <meta name="authors" content="Mathis, Hériveau, Tom Montbord, Tom Planche">
    <meta name="description" content="Proof Of Concept - SAE_3 Pole Développement">
    <meta name="viewport" content="width=device-width, height=device-height ,initial-scale=1.0">

    <link rel="stylesheet" href="../../../public/CSS/main.css">
    <link rel="stylesheet/less" type="text/css" href="../../../public/CSS/consulterDepot.scss"/>
    <script src="http://cdn.jsdelivr.net/npm/less@4.1.1"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@300&display=swap" rel="stylesheet">

    <title>Modifier un dépôt</title>
</head>

<body>
    <?php
    include '../../header.php';
    include '../../sousHeader.php';
    ?>
    <main>
        <section class="modifierDepot">
            <form >
                <table>
                    <tr style="display: none">
                        <td>id</td>
                        <td><input type="text" name="id" value="<?php echo $id; ?>"></td>
                    </tr>
                    <tr>
                        <td>
                            Titre
                        </td>
                        <td>
                            <input type="text" name="titre" value="<?php echo $titre; ?>">
                        </td>
                    </tr>
                    <tr>
                            <input type="text" name="description" value="<?php echo $description; ?>">
                    </tr>
                    <tr>
                        <td>
                            Status
                        </td>
                        <td>
                            <label>
                                <input type="checkbox" name="status" value="1" <?php if($status == 1) echo "checked"; ?>> Ouvert
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Date d'ouverture
                        </td>
                        <td>
                            <input type="date" name="dateOuverture" value="<?php echo $date_ouverture; ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Date de fermeture
                        </td>
                        <td>
                            <input type="date" name="dateFermeture" value="<?php echo $date_fermeture; ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="submit" name="Modifier" value="Modifier">
                        </td>
                    </tr>
                </table>
            </form>
        </section>
        <section class="liste-tag">

            <h1>Listes des tags</h1>
            <table>
                <tr>
                    <th>TAG</th>
                    <th>Supprimer</th>
                </tr>
                <?php
                    $sql = "select lier_tag_depot.tag_id from lier_tag_depot where lier_tag_depot.depot_id = :id";
                    $stmt = $conn_bd->prepare($sql);
                    $stmt->bindParam(':id', $id);
                    $stmt->execute();
                    $tags = $stmt->fetchAll();
                    foreach ($tags as $tag){
                        $nom = $stmt->fetch();
                        echo "<tr>";
                        echo "<td>".$tag['tag_id']."</td>";
                        echo "<td><a href='supprimerTag.php?tag=".$tag['tag_id']."&id=".$id."'>Supprimer</a></td>";
                        echo "</tr>";
                    }


                    ?>
            </table>

            <h1>Ajouter des tag</h1>
            <form action="ajouterTag.php" method="get">
                <select name="tag">
                    <?php
                        $sql = "select * from tag where tag.LABEL not in (select lier_tag_depot.tag_id from lier_tag_depot where lier_tag_depot.depot_id = :id)";
                        $stmt = $conn_bd->prepare($sql);
                        $stmt->bindParam(':id', $id);
                        $stmt->execute();
                        $tags = $stmt->fetchAll();
                        if (count($tags) == 0){
                            echo "<option value=''>Aucun tag disponible</option>";
                        }else{
                            foreach ($tags as $tag){
                                echo "<option value='".$tag['LABEL']."'>".$tag['LABEL']."</option>";
                            }
                        }
                    ?>
                </select>
                <input type="text" name="id" value="<?php echo $id; ?>" style="display: none">
                <input type="submit" name="Ajouter" value="Ajouter">
            </form>
        </section>
    </main>
    <?php
    include '../../footer.php';
    ?>


</body>



</html>