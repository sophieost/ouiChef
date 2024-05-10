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

    $entrees = getRecipesByType('entree', $season, $price, $time, $categories, $nb_jours);
    $plats = getRecipesByType('plat', $season, $price, $time, $categories, $nb_jours);
    $desserts = getRecipesByType('dessert', $season, $price, $time, $categories, $nb_jours);
    // $recipes_ids = array_column($recipes, 'id');

    // insertRecipesToMenu($menu_id, $recipes_ids);





    // Extraction des identifiants des recettes
    $entrees_ids = array_column($entrees, 'id');
    $plats_ids = array_column($plats, 'id');
    $desserts_ids = array_column($desserts, 'id');

    // Insérer les recettes dans la table menu_recettes
    // insertRecipesToMenu($menu_id, $entrees_ids, $plats_ids, $desserts_ids);



    $recipeNames = getRecipeNamesForMenu($menu_id);
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

            <?php
            // Afficher les recettes sélectionnées

            // if (!empty($recipes)) {

            for ($jour = 1; $jour <= $nb_jours; $jour++) {

                // debug($recipes);
            ?>
                <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                    <div class="card rounded-4">
                        <div class="card-body">
                            <h2 class="card-title">Jour <?= $jour ?> </h2>

                            <?php if (!empty($entrees)) : ?>
                                <h4>Entrée</h4>
                                <?php
                                foreach ($entrees_ids as $entreeId) {

                                ?>
                                    <a href="<?= RACINE_SITE ?>menus.php?showRecipe_php&id=<?= $entreeId ?>">
                                        <p class="card-text"><?= htmlspecialchars($entrees[$jour - 1]['name']) ?></p>
                                    </a>
                            <?php

                                }
                            endif;

                            ?>

                            <?php if (!empty($plats)) : ?>
                                <h4>Plat</h4>
                                <?php
                                foreach ($plats_ids as $platId) {
                                ?>
                                    <a href="<?= RACINE_SITE ?>menus.php?showRecipe_php&id=<?= $platId ?>">
                                        <p class="card-text"><?= htmlspecialchars($plats[$jour - 1]['name']) ?></p>
                                    </a>
                            <?php
                                }
                            endif;

                            ?>

                            <?php if (!empty($desserts)) : ?>
                                <h4>Dessert</h4>
                                <?php
                                foreach ($desserts_ids as $dessertId) {
                                ?>
                                    <a href="<?= RACINE_SITE ?>menus.php?showRecipe_php&id=<?= $dessertId ?>">
                                        <p class="card-text"><?= htmlspecialchars($desserts[$jour - 1]['name']) ?></p>
                                    </a>
                            <?php
                                }
                            endif;
                            ?>

                        </div>
                    </div>
                </div>

            <?php
            }
            ?>

        </div>
    </section>
</main>

<?php


if (!empty($_GET)) {

    if (isset($_GET['showRecipe_php'])) {
        require_once "showRecipe.php";
    }
}
require_once "inc/footer.inc.php";

?>