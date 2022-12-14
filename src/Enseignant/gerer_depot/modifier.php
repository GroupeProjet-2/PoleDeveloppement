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

        header('Location: consulter.php');

    }


?>

<html>
<head>
    <title>
        Modifier un dépot
    </title>
</head>

<body>
    <h1>Modifier un dépot</h1>
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
                <td>
                    Description
                </td>
                <td>
                    <input type="text" name="description" value="<?php echo $description; ?>">
                </td>
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
                echo "<td>".$tags."</td>";
                echo "<td><a href='supprimerTag.php?id=".$tag['TAG_ID']."&depot=".$id."'>Supprimer</a></td>";
                echo "</tr>";
            }


            ?>
    </table>

    <h1>Ajouter des tag</h1>
    <form action="ajouterTag.php" method="get">
        <select name="tag">
            <?php
                $sql = "select * from tag where LABEL not in (select lier_tag_depot.tag_id from lier_tag_depot where lier_tag_depot.depot_id = :id)";
                $stmt = $conn_bd->prepare($sql);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                $tags = $stmt->fetchAll();
                foreach ($tags as $tag){
                    echo "<option value='".$tag['ID']."'>".$tag['LABEL']."</option>";
                }

            ?>
        </select>
        <input type="text" name="id" value="<?php echo $id; ?>" style="display: none">
        <input type="submit" name="Ajouter" value="Ajouter">
    </form>


</body>



</html>