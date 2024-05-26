<?php

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

require_once "inc/functions.inc.php";


// if (!isset($_SESSION['user'])) {
//     header("Location: identification.php");
//     exit();
// }

$info = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Récupération des données du formulaire
    $nb_jours = $_POST['nb_jours'];
    $nb_pers = $_POST['nb_pers'];
    $time = isset($_POST['time']) ? $_POST['time'] : null;
    $price = isset($_POST['price']) ? $_POST['price'] : null;
    $season = isset($_POST['season']) ? $_POST['season'] : null;
    $categories = isset($_POST['categories']) ? $_POST['categories'] : [];

    $menu_id = addMenu($_SESSION['user']['id'], $nb_jours, $nb_pers);

    $entrees = getRecipesByType('entree', $season, $price, $time, $categories, $nb_jours);
    $plats = getRecipesByType('plat', $season, $price, $time, $categories, $nb_jours);
    $desserts = getRecipesByType('dessert', $season, $price, $time, $categories, $nb_jours);

    $entrees_ids = array_column($entrees, 'id');
    $plats_ids = array_column($plats, 'id');
    $desserts_ids = array_column($desserts, 'id');

    if (empty($entrees) && empty($plats) && empty($desserts)) {
        echo "Il n'existe aucune recette avec les critères sélectionnés.";
    } else {
        insertRecipesToMenu($menu_id, $entrees_ids, $plats_ids, $desserts_ids);
        header("Location: " . RACINE_SITE . "menus.php?action=add&id_menu=" . $menu_id);
        exit();
    }
}

$title = "Accueil";
require_once "inc/header.inc.php";

?>

<main class="mainAccueil">
    <section class="generator">

        <div class="card container p-3">
            <h1 class="text-center fs-1 my-2">MON MENU</h1>

            <form action="" method="post" id="monFormulaire">

                <div class="row">

                    <div class="col-lg-6 col-sm-12">
                        <div class="days mt-3 d-flex flex-column justify-content-center align-items-center">
                            <label for="nb_jours" class="form-label fw-bold"> Nombre de jours</label>
                            <input type="range" id="nb_jours" name="nb_jours" min="1" max="30" step="1">
                            <p class="fs-4 w-50 d-flex justify-content-center"><output id="valueJours"></output><i class="bi bi-calendar-day ms-2"></i></p>
                        </div>
                    </div>

                    <div class="col-lg-6 col-sm-12">
                        <div class="people mt-3 d-flex flex-column justify-content-center align-items-center">
                            <label for="nb_pers" class="form-label fw-bold"> Nombre de personnes</label>
                            <input type="range" id="nb_pers" name="nb_pers" min="1" max="16" step="1">
                            <p class="fs-4 w-50 d-flex justify-content-center"><output id="valuePers"></output><i class="bi bi-person-fill fs-4 ms-2"></i></p>
                        </div>
                    </div>
                </div>

                <div class="row align-items-center check">
                    <div class="col-lg-6 col-md-12 px-3 text-center">
                        <div class="d-flex flex-column mt-3 align-items-center">
                            <label for="" class=" fw-bold"> Temps de préparation</label>
                            <div class="d-flex flex-column mt-2">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="time" id="rapide" value="rapide">
                                    <label class="form-check-label" for="rapide">Rapide</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="time" id="moyen" value="moyen">
                                    <label class="form-check-label" for="moyen">Moyen</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="time" id="long" value="long">
                                    <label class="form-check-label" for="long">Long</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-12 px-3 text-center">
                        <div class="d-flex flex-column mt-3 align-items-center">
                            <label for="" class=" fw-bold"> Prix</label>
                            <div class="d-flex flex-column mt-2">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="price" id="bonMarche" value="bonMarche">
                                    <label class="form-check-label" for="bonMarche">Petit budget</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="price" id="raisonnable" value="raisonnable">
                                    <label class="form-check-label" for="raisonnable">Budget moyen</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="price" id="cher" value="cher">
                                    <label class="form-check-label" for="cher">Gros budget</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row check">
                    <div class="col-lg-6 col-md-12 season">
                        <div class="d-flex flex-column mt-3 ms-5 align-items-center">
                            <label for="" class=" fw-bold"> Saison</label>
                            <div class="d-flex flex-column mt-2">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="season" id="ete" value="ete">
                                    <label class="form-check-label" for="ete">Été</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="season" id="hiver" value="hiver">
                                    <label class="form-check-label" for="hiver">Hiver</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="season" id="all" value="all">
                                    <label class="form-check-label" for="all">Toutes saisons</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-12">
                        <div class="d-flex justify-content-center">
                            <button class="btn bg-white btnOptions my-5">Plus d'options</button>
                        </div>
                    </div>
                </div>

                <div class="options pt-3">
                    <h2>Mes préférences</h2>
                    <div class="py-3">
                        <?php
                        $categories = allCategories();
                        foreach ($categories as $category) {
                            $categoryId = $category['id'];
                        ?>
                            <label for="category_<?= $categoryId ?>" class="m-2"><input type="checkbox" name="categories[]" id="category_<?= $categoryId ?>" value="<?= $categoryId ?>"><span class="label p-2"><?= $category['name'] ?></span></label>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <?php
                if (empty($_SESSION['user'])) {
                ?>
                    <button class="btnRedirection"><a href="<?= RACINE_SITE ?>identification.php" class=" text-white">C'est parti !</a></button>
                <?php
                } else {
                ?>
                    <input type="submit" value="C'est parti !">
                <?php
                }
                ?>
            </form>
        </div>

    </section>

    <section class="presentation container">
        <h2 class="text-center mb-5">Le concept : un générateur de menu pour trouver l'inspiration.</h2>
        <p class="mx-5">OUI CHEF vous suggère des menus personnalisés en fonction de vos goûts, envies, du temps que vous avez devant vous ou de votre budget.</p>
        <p class="mx-5">Une liste de course peut être réalisée à partir des ingrédients des recettes que vous avez choisies. Vous pouvez l'imprimer ou la retrouver sur votre espace personnel depuis votre smartphone</p>

        <h2 class="text-center my-5">Comment ça marche ? c'est simple, vous décidez :</h2>
        <ul class="mx-5">
            <li>Du nombre de menus.</li>
            <li>Du nombre de personnes.</li>
            <li>De vos préférences.</li>
        </ul>
        <p class="m-5">Ensuite vous pourrez affiner les menus en modifiant un plat qui ne vous plairait pas. Vous pourrez également liker les plats qui vous ont plu ou écarter les plats qui ne vous plaisent pas. Les recettes likées seront conservées dans votre librairie.</p>
    </section>


</main>

<?php
require_once "inc/footer.inc.php";
?>