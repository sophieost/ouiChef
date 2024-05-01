<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "inc/functions.inc.php";

if (!isset($_SESSION['user'])) {
    header("location: identification.php");
    exit();
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $nb_jours = $_POST['nb_jours'];
    $nb_pers = $_POST['nb_pers'];
    $time = isset($_POST['time']) ? $_POST['time'] : null;
    $price = isset($_POST['price']) ? $_POST['price'] : null;
    $season = isset($_POST['season']) ? $_POST['season'] : null;
    $categories = isset($_POST['categories']) ? $_POST['categories'] : [];

    // var_dump($_POST['price']);

    // Ajout du menu à la table menus
    $menu = addMenu($_SESSION['user']['id'], $nb_jours, $nb_pers);

    // Récupération des recettes correspondantes
    $recipes = getRecipesForm($season, $price, $time, $categories, $nb_jours);
}


if (isset($_GET['action']) && isset($_GET['id_menu'])) {

    if (!empty($_GET['action']) && $_GET['action'] == 'add' && !empty($_GET['id_menu'])) {

        $menu_id = $_GET['id_menu'];

        if (isset($recipes)) {
            $menu = insertRecipesToMenu($menu_id, $recipes);
        }
    }
}



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
            
            if (!empty($recipes)) {
                foreach ($recipes as $recipe) {


                    debug($recipes);
            ?>
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-3">

                        <div class="card rounded-4">
                            <div class="card-body">
                                <h2 class="card-title">Jour </h2>
                                <h4>Entrée</h4>
                                <a href="">
                                    <p class="card-text"><?= $recipe['name'] ?></p>
                                </a>

                                <h4>Plat</h4>
                                <a href="">
                                    <p class="card-text"><?= $recipe['name'] ?></p>
                                </a>

                                <h4>Dessert</h4>
                                <a href="">
                                    <p class="card-text"><?= $recipe['name'] ?></p>
                                </a>

                            </div>
                        </div>

                    </div>
            <?php
                }
            }
            ?>

        </div>
    </section>
</main>

<?php
require_once "inc/footer.inc.php";
?>