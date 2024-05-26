<?php

require_once "inc/functions.inc.php";



if (empty($_SESSION['user'])) {

    header("location:" . RACINE_SITE . "identification.php");
} else if ($_SESSION['user']['role'] == 'admin') {

    header("location:" . RACINE_SITE . "admin/dashboard.php");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_FILES['image'])) {

        $id = $_SESSION['user']['id'];
        $image = basename($_FILES['image']['name']);

        if (move_uploaded_file($_FILES['image']['tmp_name'], 'assets/img/' . $image)) {

            // debug($_POST);

            updateImgUser($id, $image);

            $user = showUser($id);
        } else {
            $info = 'Erreur lors du déplacement du fichier téléchargé.';
        }
    } else {
        $info = 'Erreur lors du téléchargement du fichier.';
    }
}

logOut();


$title = "Profil";
require_once "inc/header.inc.php";
$info = '';

?>

<main class="py-5" id="profil">

    <div class="row py-5 container align-items-center mx-auto">
        <div class="d-flex flex-column col-md-4 col-sm-12">
        <img src="<?= RACINE_SITE . "assets/img/" . ($user['image'] ?? 'avatar-chef.jpg') ?>" alt="Avatar" class="avatar-img mx-auto">

            <form action="" method="post" enctype="multipart/form-data" class="mx-auto">
                <label for="avatar">Changer</label>
                <input type="file" id="avatar" name="image" value="<?= $user['image'] ?? '' ?>" class="d-none">
                <input type="submit" value="ok" class="submitImg">

            </form>
        </div>
        <div class="col-md-8 col-sm-12 text-center">
            <h4><?= $_SESSION['user']['pseudo'] ?></h4>
            <h3>MON PROFIL</h3>
        </div>
    </div>

    <div class="row container align-items-center mx-auto">

        <div class="col-md-5 col-sm-12 onglets">
            <!-- Les onglets du menu -->
            <div class="onglet"><a href="?informations_php">Mes informations</a></div>
            <div class="onglet"><a href="?favoris_php">Mes favoris</a></div>
            <div class="onglet"><a href="?action=deconnexion">Déconnexion</a></div>
        </div>

        <div id="contenu" class="col-md-7 col-sm-12">
            <!-- Le contenu qui change en fonction de l'onglet sélectionné -->
            <?php
            if (!empty($_GET)) {

                if (isset($_GET['informations_php'])) {
                    require_once "informations.php";
                } else if (isset($_GET['favoris_php'])) {
                    require_once "favoris.php";
                } else {
                    require_once "profil.php";
                }
            }
            ?>
        </div>

    </div>

</main>



<?php

require_once "inc/footer.inc.php";

?>