<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "inc/functions.inc.php";

if (!isset($_SESSION['user'])) {
    header("location: identification.php");
    exit();
}



if (!empty($_GET['action']) && $_GET['action'] == 'add' && !empty($_GET['id_menu'])) {

    $menu_id = $_GET['id_menu'];


    $infosMenu = getMenuInfoById($menu_id);

    $nb_jours = $infosMenu['nb_jours'];
    $nb_pers = $infosMenu['nb_pers'];


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




    // debug($desserts);
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
                                <?php $entree = current($entrees); // Récupère l'entrée courante ?>
                                <a href="<?= RACINE_SITE ?>menus.php?showRecipe_php&id=<?= $entree['id'] ?>">
                                    <p class="card-text"><?= htmlspecialchars_decode($entree['name']) ?></p>
                                </a>
                                <?php next($entrees); // Passe à l'entrée suivante ?>
                            <?php endif; ?>

                            <?php if (!empty($plats)) : ?>
                                <h4>Plat</h4>
                                <?php $plat = current($plats); // Récupère le plat courant ?>
                                <a href="<?= RACINE_SITE ?>menus.php?showRecipe_php&id=<?= $plat['id'] ?>">
                                    <p class="card-text"><?= htmlspecialchars_decode($plat['name']) ?></p>
                                </a>
                                <?php next($plats); // Passe au plat suivant ?>
                            <?php endif; ?>

                            <?php if (!empty($desserts)) : ?>
                                <h4>Dessert</h4>
                                <?php $dessert = current($desserts); // Récupère le dessert courant ?>
                                <a href="<?= RACINE_SITE ?>menus.php?showRecipe_php&id=<?= $dessert['id'] ?>">
                                    <p class="card-text"><?= htmlspecialchars_decode($dessert['name']) ?></p>
                                </a>
                                <?php next($desserts); // Passe au dessert suivant ?>
                            <?php endif; ?>

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