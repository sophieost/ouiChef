<?php
require_once "inc/functions.inc.php";

$title = "Les recettes";



require_once "inc/header.inc.php";

$info = "";

$recipes = allRecipes();




if (isset($_GET) && !empty($_GET)) {
    if (isset($_GET['bon-marche'])) {
        $recipes = recipesByPrice($_GET['bon-marche']);
        $message = "recettes trouvées.";
    } else if (isset($_GET['raisonnable'])) {
        $recipes = recipesByPrice($_GET['raisonnable']);
        $message = "recettes trouvées.";
    } else if (isset($_GET['cher'])) {
        $recipes = recipesByPrice($_GET['cher']);
        $message = "recettes trouvées.";
    } else if (isset($_GET['cher'])) {
        $recipes = recipesByPrice($_GET['cher']);
        $message = "recettes trouvées.";
    } else if (isset($_GET['cher'])) {
        $recipes = recipesByPrice($_GET['cher']);
        $message = "recettes trouvées.";
    } else if (isset($_GET['cher'])) {
        $recipes = recipesByPrice($_GET['cher']);
        $message = "recettes trouvées.";
    } else if (isset($_GET['cher'])) {
        $recipes = recipesByPrice($_GET['cher']);
        $message = "recettes trouvées.";
    } else {
        $recipes = allRecipes();
    }

    if (isset($recipes) && count($recipes) == 0) {
        $info = alert("Aucune recette trouvée pour ces critères", "secondary");
    }
}
?>










<main id="lesRecettes">
    <div class="row container mx-auto">

        <div class="col text-center">
            <button class="btn"><a href="<?= RACINE_SITE ?>recettes.php?allecipes" class="text-decoration-none text-dark">Toutes les recettes</a></button>
        </div>

        <div class="dropdown col text-center">
            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                Par prix
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="<?= RACINE_SITE ?>recettes.php?price=bon-marche">Bon marché</a>
                <a class="dropdown-item" href="<?= RACINE_SITE ?>recettes.php?price=raisonnable">Raisonnable</a>
                <a class="dropdown-item" href="<?= RACINE_SITE ?>recettes.php?price=cher">Cher</a>
            </div>
        </div>


        <div class="dropdown col text-center">
            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                Par temps de préparation
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="<?= RACINE_SITE ?>recettes.php?time=rapide">Rapide</a>
                <a class="dropdown-item" href="<?= RACINE_SITE ?>recettes.php?time=moyen">Moyen</a>
                <a class="dropdown-item" href="<?= RACINE_SITE ?>recettes.php?time=long">Long</a>
            </div>
        </div>


        <div class="dropdown col text-center">
            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                Par catégories
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="<?= RACINE_SITE ?>recettes.php?category=">Bon marché</a>
            </div>
        </div>

        <div class="dropdown col text-center">
            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                Par saison
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="<?= RACINE_SITE ?>recettes.php?season=ete">Eté</a>
                <a class="dropdown-item" href="<?= RACINE_SITE ?>recettes.php?season=hiver">Hiver</a>
            </div>
        </div>

        <div class="dropdown col text-center">
            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
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

        <h5 class="my-5 mx-5"><span class="nbreAffiche"><?= count($recipes) ?></span> <?= ($message) ?? "recettes" ?></h5>

        <div class="row my-5">

            <?php
            echo $info;

            foreach ($recipes as $recipe) {
            ?>

                <div class="col-lg-4 col-md-6 col-sm-12">

                    <div class="card rounded">

                        <div class="card-body">
                            <h5 class="card-title"><?= $recipe['name'] ?></h5>
                            <img src="<?= RACINE_SITE . "assets/img/" . $recipe['image'] ?>" class="card-img" alt="image de . <?= $recipe['name'] ?>">
                            <ul class="list-unstyled">
                                <?php if (isset($recipe['prix'])) : ?>
                                    <li>Prix : <?= ucfirst($recipe['price']) ?></li>
                                <?php endif; ?>
                                <?php if (isset($recipe['time'])) : ?>
                                    <li>Temps de préparation : <?= ucfirst($recipe['time']) ?></li>
                                <?php endif; ?>
                                <?php if (isset($recipe['season'])) : ?>
                                    <li>Saison : <?= ucfirst($recipe['season']) ?></li>
                                <?php endif; ?>
                            </ul>

                            <?php

                            $categories = showCategoriesRecipe($recipe['id']);
                            foreach ($categories as $category) {


                            ?>

                                <span><?=$category['name']?></span>

                            <?php

                            }
                            ?>
                            <a href="#" class="btn btn-primary">Ajouter aux favoris</a>
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