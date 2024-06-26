<?php

require_once "../inc/functions.inc.php";


if(isset($_GET['action']) && isset($_GET['id'])){
    if(!empty($_GET['action']) && $_GET ['action']=='delete' && !empty($_GET['id'])){
        $idUser = htmlentities($_GET['id']);

        deleteUser($idUser);
    }
}

if(!empty($_GET['action']) && $_GET['action'] == 'update' && !empty($_GET['id'])){
    $user = showUser($_GET['id']);
    if($user['role']=='admin'){
        updateRole('user',$user['id']);
        // header("location:".RACINE_SITE."admin/dashboard.php?users_php");

    }

    if($user['role']=='user'){
        updateRole('admin',$user['id']);
        // header("location:".RACINE_SITE."admin/dashboard.php?users_php");

    }
}


if( !isset($_SESSION['user'])){
    header("location:".RACINE_SITE."identification.php");
}else{
    if($_SESSION['user']['role'] == 'user'){
        header("location:".RACINE_SITE."index.php");
    }
}


$metadescription = "Votre centre de commande culinaire. Surveillez l'activité du site, gérez les recettes et les utilisateurs le tout depuis le tableau de bord de votre backoffice.";

$title = 'Tableau de Bord - Administration générale';

require_once "../inc/header.inc.php";
?>



<main class="py-5" id="dashboard">

    <div class="row py-5 align-items-center mx-auto">
        <div class="d-flex flex-column col-md-4 col-sm-12">
            <img src="<?= RACINE_SITE ?>assets/img/avatar-chef.jpg" alt="avatar" class="avatar-img mx-auto">

        </div>
        <div class="col-md-8 col-sm-12 text-center">
            <h4><?= $_SESSION['user']['pseudo'] ?></h4>
            <h3>MON BACK OFFICE</h3>
        </div>
    </div>



        <div class="onglets row container mx-auto">
            <!-- Les onglets du menu -->
            <a href="?users_php" class="onglet col-lg-2 col-md-4 col-sm-12 shadow"><div>Utilisateurs</div></a>
            <a href="?recipes_php" class="onglet col-lg-2 col-md-4 col-sm-12 shadow"><div>Recettes</div></a>
            <a href="?categories_php" class="onglet col-lg-2 col-md-4 col-sm-12 shadow"><div>Catégories</div></a>
            <a href="?ingredients_php" class="onglet col-lg-2 col-md-4 col-sm-12 shadow"><div>Ingrédients</div></a>
        </div>

        <div id="contenu">
            <!-- Le contenu qui change en fonction de l'onglet sélectionné -->
            <?php
            if (!empty($_GET)) {

                if (isset($_GET['users_php'])) {
                    require_once "users.php";

                } else if (isset($_GET['recipes_php'])) {
                    require_once "recipes.php";

                } else if (isset($_GET['categories_php'])) {
                    require_once "categories.php";

                } else if (isset($_GET['ingredients_php'])) {
                    require_once "ingredients.php";

                } else {
                    require_once "dashboard.php";
                }
            }
            ?>
        </div>





</main>





<?php
require_once "../inc/footer.inc.php";

?>