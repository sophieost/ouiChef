<?php

require_once "inc/functions.inc.php";


$info = '';

if (isset($_SESSION['user'])) {
    $userId = $_SESSION['user']['id'];
} else {
    $infos = "Veuillez vous connecter pour voir vos recettes favorites.";
}



$title = 'Mes favoris';

require_once "inc/header.inc.php";

echo $info;

?>


<section>

    <div class="bg-white userFav pt-5">
        <h2 class="text-center mb-5">Mes recettes préférées</h2>
        <ul>

            <?php

            $favorites = allFavoriteRecipes($userId);
            // debug($favorites);
            if (empty($favorites)) {

                echo $info .= "<p>Vous n'avez pas encore de recettes favorites.</p>";
            } else {

                foreach ($favorites as $favorite) {

                    showRecipe($favorite['recipe_id']);


            ?>

                    <li class="list-unstyled d-flex justify-content-between mx-5 my-3"><a href="showRecipe.php?id=<?= $favorite['recipe_id'] ?>" class="text-decoration-none text-dark"><?= htmlspecialchars(ucfirst($favorite['name'])) ?></a><a href="favoris.php?action=add&id=<?= $favorite['recipe_id'] ?>"><i class="bi bi-trash3"></i></a></li>

            <?php
                }
            }
            ?>

        </ul>
    </div>

    <div class="mt-3 bg-white userFav pt-5">

        <h2 class="text-center mb-5">Mes recettes blacklistées</h2>

        <ul>

            <?php

            $favoriteRecipesIds = allBlacklistRecipes($userId);

            foreach ($favoriteRecipesIds as $favorite) {
                showRecipe($favorite['recipe_id']);

            ?>

                <li><?= htmlspecialchars($favorite['name']) ?><a href="favoris.php?action=delete&id=<?= $favorite['recipe_id'] ?>"><i class="bi bi-arrow-counterclockwise"></i></a></li>

            <?php

            }
            
            ?>
        </ul>
    </div>

</section>