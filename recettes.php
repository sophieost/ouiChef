<?php
require_once "inc/functions.inc.php";


$metadescription = "Explorez une variété de recettes savoureuses et trouvez la perfection culinaire. Triez par ingrédients, temps de préparation, ou type de plat pour personnaliser votre expérience gastronomique.";

$title = "Découvrez des Recettes Uniques - Votre Bibliothèque Culinaire";

require_once "inc/header.inc.php";



$info = "";

if (isset($_GET['action']) && isset($_GET['id'])) {
    if (!empty($_GET['action']) && !empty($_GET['id'])) {

        if (isset($_SESSION['user'])) {

            $userId = $_SESSION['user']['id'];

            $recipeId = htmlentities($_GET['id']);

            if ($_GET['action'] == 'fav') {

                addRecipeToFavorites($userId, $recipeId);



                $info = alert("Recette ajoutée aux favoris", "success");
            } elseif ($_GET['action'] == 'blacklist') {

                addRecipeToBlacklist($userId, $recipeId);



                $info = alert("Recette ajoutée à la blacklist", "success");
            }
        }
    }
}


if (isset($_GET)) {
    if (isset($_GET['price'])) {
        $recipes = recipesByPrice($_GET['price']);
    } else if (isset($_GET['time'])) {
        $recipes = recipesByTime($_GET['time']);
    } else if (isset($_GET['category'])) {
        $recipes = recipesByCategory($_GET['category']);
    } else if (isset($_GET['season'])) {
        $recipes = recipesBySeason($_GET['season']);
    } else if (isset($_GET['plat'])) {
        $recipes = recipesByPlat($_GET['plat']);
    } else if (isset($_GET['allrecipes'])) {
        $recipes = allRecipes();
    } else {

        $recipes = allRecipes();
    }

    // if (count($recipes) == 0) {
    //     $info = alert("Aucune recette trouvée pour ces critères", "secondary");
    // }
}
?>





<main id="lesRecettes" class="py-5">
    <div class="d-flex container mx-auto recettesDiv flex-wrap">

        <div class="col text-center">
            <button class="btn border-0"><a href="<?= RACINE_SITE ?>recettes.php?allrecipes" class="text-decoration-none">Toutes les recettes</a></button>
        </div>

        <div class="dropdown col text-center">
            <button class="btn border-0 dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                Par prix
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="<?= RACINE_SITE ?>recettes.php?price=bonMarche">Bon marché</a>
                <a class="dropdown-item" href="<?= RACINE_SITE ?>recettes.php?price=raisonnable">Raisonnable</a>
                <a class="dropdown-item" href="<?= RACINE_SITE ?>recettes.php?price=cher">Cher</a>
            </div>
        </div>


        <div class="dropdown col text-center">
            <button class="btn  border-0 dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                Par temps de préparation
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="<?= RACINE_SITE ?>recettes.php?time=rapide">Rapide</a>
                <a class="dropdown-item" href="<?= RACINE_SITE ?>recettes.php?time=moyen">Moyen</a>
                <a class="dropdown-item" href="<?= RACINE_SITE ?>recettes.php?time=long">Long</a>
            </div>
        </div>


        <div class="dropdown col text-center">
            <button class="btn  border-0 dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                Par catégories
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <?php

                $categories = allCategories();
                foreach ($categories as $category) {

                ?>
                    <a class="dropdown-item" href="<?= RACINE_SITE ?>recettes.php?category=<?= $category['name'] ?>"><?= ucfirst($category['name']) ?></a>
                <?php
                }
                ?>
            </div>
        </div>

        <div class="dropdown col text-center">
            <button class="btn  border-0 dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                Par saison
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="<?= RACINE_SITE ?>recettes.php?season=ete">Eté</a>
                <a class="dropdown-item" href="<?= RACINE_SITE ?>recettes.php?season=hiver">Hiver</a>
            </div>
        </div>

        <div class="dropdown col text-center">
            <button class="btn  border-0 dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                Par type de plat
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="<?= RACINE_SITE ?>recettes.php?plat=entree">Entrées</a>
                <a class="dropdown-item" href="<?= RACINE_SITE ?>recettes.php?plat=plat">Plats</a>
                <a class="dropdown-item" href="<?= RACINE_SITE ?>recettes.php?plat=dessert">Desserts</a>
            </div>
        </div>

    </div>

    <section class="container">

        <h1 class="nbreAffiche my-5 mx-5 fs-3"><?= count($recipes) . " recettes trouvées" ?? "recettes" ?></h1>

        <div class="row my-5">

            <?php
            echo $info;

            foreach ($recipes as $recipe) {
            ?>

                <div class="col-lg-4 col-md-6 col-sm-12 mb-3">

                    <div class="card rounded-4 h-100">

                        <div class="card-body d-flex justify-content-between flex-column">

                            <h5 class="card-title text-center mb-3"><?= ucfirst($recipe['name']) ?></h5>

                            <img src="<?= RACINE_SITE . "assets/img/" . $recipe['image'] ?>" class="card-img h-50" alt="image de . <?= $recipe['name'] ?>">

                            <ul class="list-unstyled mt-3 d-flex jsutify-content-around infosCard">

                                <?php if (isset($recipe['price'])) : ?>
                                    <li class="px-3">

                                        <?php
                                        switch ($recipe['price']) {
                                            case 'bonMarche':
                                                echo 'Bon marché <i class="bi bi-currency-euro"></i>';
                                                break;
                                            case 'raisonnable':
                                                echo 'Raisonnable <i class="bi bi-currency-euro"></i><i class="bi bi-currency-euro"></i>';
                                                break;
                                            case 'cher':
                                                echo 'Cher <i class="bi bi-currency-euro"></i><i class="bi bi-currency-euro"></i><i class="bi bi-currency-euro"></i>';
                                                break;
                                        }
                                        ?>
                                    </li>
                                <?php endif; ?>

                                <?php if (isset($recipe['time'])) : ?>
                                    <li class="px-3">
                                        <?= ucfirst($recipe['time']) ?>
                                        <?php
                                        switch ($recipe['time']) {
                                            case 'rapide':
                                                echo '<i class="bi bi-clock"></i>';
                                                break;
                                            case 'moyen':
                                                echo '<i class="bi bi-clock"></i><i class="bi bi-clock"></i>';
                                                break;
                                            case 'long':
                                                echo '<i class="bi bi-clock"></i><i class="bi bi-clock"></i><i class="bi bi-clock"></i>';
                                                break;
                                        }
                                        ?>
                                    </li>
                                <?php endif; ?>

                                <?php if (isset($recipe['season'])) : ?>
                                    <li class="px-3"><?= $recipe['season'] != 'all' ? ucfirst($recipe['season']) : '' ?></li>
                                <?php endif; ?>
                            </ul>

                            <ul class="list-unstyled mt-3 d-flex flex-wrap">
                                <?php

                                $categories = showCategoriesRecipe($recipe['id']);
                                foreach ($categories as $category) {

                                ?>
                                    <li class="tag"><?= $category['name'] ?></li>
                                <?php

                                }
                                ?>
                            </ul>

                            <div class="d-flex justify-content-between align-items-center favParent">
                                <a href="<?= RACINE_SITE . "showRecipe.php?id=" . $recipe['id'] ?>" class="btn border">Voir la recette</a>

                                <?php
                                if (!empty($_SESSION['user'])) {

                                    $isFavorite = isRecipeFavorite($_SESSION['user']['id'], $recipe['id']);
                                    $isBlacklist = isRecipeBlacklist($_SESSION['user']['id'], $recipe['id']);
                                ?>

                                    <div>
                                        <a href="recettes.php?action=fav&id=<?= $recipe['id'] ?>" class="linkFav">
                                            <i class="bi <?= $isFavorite ? 'bi-heart-fill' : 'bi-heart' ?> fs-3 iconFav <?= $isFavorite ? 'text-danger' : 'text-dark' ?>"></i>
                                        </a>

                                        <a href="recettes.php?action=blacklist&id=<?= $recipe['id'] ?>" class="linkFav">
                                            <i class="bi <?= $isBlacklist ? 'bi-emoji-frown-fill' : 'bi-emoji-frown' ?> fs-3 iconFav mx-3 <?= $isBlacklist ? 'text-danger' : 'text-dark' ?>"></i>
                                        </a>
                                    </div>



                                <?php
                                }
                                ?>
                            </div>

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
require_once "inc/footer.inc.php";


?>