<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "inc/functions.inc.php";

if (!isset($_SESSION['user'])) {
    header("location: identification.php");
    exit();
}

$nb_jours = 0; // Initialisation de la variable avec une valeur par défaut
$nb_pers = 0;

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


    
}






$title = "Menus";
require_once "inc/header.inc.php";
?>
<main id="menus">

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
                                <?php $entree = current($entrees); // Récupère l'entrée courante 
                                ?>
                                <a href="#" data-id="<?= $entree['id'] ?>" class="recipe-link">
                                    <p class="card-text"><?= htmlspecialchars_decode($entree['name']) ?></p>
                                </a>
                                <?php next($entrees); // Passe à l'entrée suivante 
                                ?>
                            <?php endif; ?>

                            <?php if (!empty($plats)) : ?>
                                <h4>Plat</h4>
                                <?php $plat = current($plats); // Récupère le plat courant 
                                ?>
                                <a href="#" data-id="<?= $plat['id'] ?>" class="recipe-link">
                                    <p class="card-text"><?= htmlspecialchars_decode($plat['name']) ?></p>
                                </a>
                                <?php next($plats); // Passe au plat suivant 
                                ?>
                            <?php endif; ?>

                            <?php if (!empty($desserts)) : ?>
                                <h4>Dessert</h4>
                                <?php $dessert = current($desserts); // Récupère le dessert courant 
                                ?>
                                <a href="#" data-id="<?= $dessert['id'] ?>" class="recipe-link">
                                    <p class="card-text"><?= htmlspecialchars_decode($dessert['name']) ?></p>
                                </a>
                                <?php next($desserts); // Passe au dessert suivant 
                                ?>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            <?php endfor; ?>
        </div>

        <div id="scroll"></div>

        <div id="recipe-content">

        </div>
</main>

<script>


    //   APPARITION DES RECETTES SUR LA PAGE MENUS  //

    document.addEventListener('DOMContentLoaded', function() {
        var recipeLinks = document.querySelectorAll('.recipe-link');
    
        recipeLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                var recipeId = this.getAttribute('data-id');
    
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'showRecipe.php?id=' + recipeId, true);
                xhr.onload = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var recipeContent = document.getElementById('recipe-content');
                        recipeContent.innerHTML = xhr.responseText;
                        recipeContent.scrollIntoView({ behavior: 'smooth' });
                    }
                };
                xhr.send();
            });
        });
    });

</script>


<?php


// if (!empty($_GET)) {

//     if (isset($_GET['showRecipe_php'])) {
//         require_once "showRecipe.php";
//     }
// }
require_once "inc/footer.inc.php";

?>