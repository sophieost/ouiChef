<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "inc/functions.inc.php";

if (!isset($_SESSION['user'])) {
    header("location: identification.php");
    exit();
}




// Récupération des données du formulaire


// var_dump($_POST['price']);


// if (isset($_GET['action']) && isset($_GET['id_menu'])) {

if (!empty($_GET['action']) && $_GET['action'] == 'add' && !empty($_GET['id_menu'])) {

    $menu_id = $_GET['id_menu'];


    $infosMenu = getMenuInfoById($menu_id);

    $nb_jours = $infosMenu['nb_jours'];
    $nb_pers = $infosMenu['nb_pers'];
    $time = isset($_POST['time']) ? $_POST['time'] : null;
    $price = isset($_POST['price']) ? $_POST['price'] : null;
    $season = isset($_POST['season']) ? $_POST['season'] : null;
    $categories = isset($_POST['categories']) ? $_POST['categories'] : [];

    $entreesId = getRecipesByType('entree', $season, $price, $time, $categories, $nb_jours);
    $platsId = getRecipesByType('plat', $season, $price, $time, $categories, $nb_jours);
    $dessertsId  = getRecipesByType('dessert', $season, $price, $time, $categories, $nb_jours);



    // $recipes_ids = array_column($recipes, 'id');

    // insertRecipesToMenu($menu_id, $recipes_ids);





    // Extraction des identifiants des recettes

    $entrees_ids = array_column($entreesId, 'id');
    $plats_ids = array_column($platsId, 'id');
    $desserts_ids = array_column($dessertsId, 'id');

    // Insérer les recettes dans la table menu_recettes
    // insertRecipesToMenu($menu_id, $entrees_ids, $plats_ids, $desserts_ids);



    // $recipeNames = getRecipeNamesForMenu($menu_id);

    $recettes = getRecipeNamesForMenu($menu_id);

    $entrees = array_filter($recettes, function ($recette) {
        return $recette['typePlat'] === 'entree';
    });

    $plats = array_filter($recettes, function ($recette) {
        return $recette['typePlat'] === 'plat';
    });

    $desserts = array_filter($recettes, function ($recette) {
        return $recette['typePlat'] === 'dessert';
    });
}
// }




// debug($_POST);



$title = "Recettes";
require_once "inc/header.inc.php";
?>
<main>

    <div class="d-flex justify-content-between container">
        <h2>Mon menu</h2>
        <button class="btn"><a href="<?= RACINE_SITE ?>liste.php">Voir ma liste de courses</a></button>
    </div>

    <section>

        <div class="row my-5">
            <?php for ($jour = 1; $jour <= $nb_jours; $jour++) : ?>
                <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                    <div class="card rounded-4">
                        <div class="card-body">
                            <h2 class="card-title">Jour <?= $jour ?> </h2>

                            <?php if (!empty($entrees)) : ?>
                                <h4>Entrée</h4>
                                <?php foreach ($entrees as $entree) { ?>
                                    <a href="<?= RACINE_SITE ?>menus.php?showRecipe_php&id=<?= $entree['id'] ?>">
                                        <p class="card-text"><?= htmlspecialchars($entree['name']) ?></p>
                                    </a>

                            <?php
                                }
                            endif;

                            ?>

                            <?php if (!empty($plats)) : ?>
                                <h4>Plat</h4>
                                <?php foreach ($plats as $plat) { ?>
                                    <a href="<?= RACINE_SITE ?>menus.php?showRecipe_php&id=<?= $plat['id'] ?>">
                                        <p class="card-text"><?= htmlspecialchars($plat['name']) ?></p>
                                    </a>

                            <?php
                                }
                            endif;
                            ?>

                            <?php if (!empty($desserts)) : ?>
                                <h4>Dessert</h4>
                                <?php foreach ($desserts as $dessert) { ?>
                                    <a href="<?= RACINE_SITE ?>menus.php?showRecipe_php&id=<?= $dessert['id'] ?>">
                                        <p class="card-text"><?= htmlspecialchars($dessert['name']) ?></p>
                                    </a>

                            <?php
                                }
                            endif;
                            ?>

                        </div>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
</main>

<?php


if (!empty($_GET)) {

    if (isset($_GET['showRecipe_php'])) {
        require_once "showRecipe.php";
    }
}
require_once "inc/footer.inc.php";

?>