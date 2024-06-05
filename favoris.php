<?php

require_once "inc/functions.inc.php";


$info = '';

if (isset($_SESSION['user'])) {
    $userId = $_SESSION['user']['id'];
} else {
    $infos = "Veuillez vous connecter pour voir vos recettes favorites.";
}

if (isset($_GET['action']) && isset($_GET['id'])) {
    if (!empty($_GET['action']) && $_GET['action'] == 'delete' && !empty($_GET['id'])) {



        $userId = $_SESSION['user']['id'];
        $id = $_GET['id'];
        deleteFavorite($id);

        $info = alert("Recette retirée", "success");
    }
}

$metadescription = "Consultez et organisez vos recettes préférées et celles bloquées sur OuiChef. Accédez facilement à vos coups de cœur culinaires et gérez votre liste de recettes à éviter.";

$title = 'Vos Favoris et Blacklist - Gérez Vos Recettes sur OuiChef';

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

                echo $info = "<p>Vous n'avez aucune recette favorite.</p>";
            } else {

                foreach ($favorites as $favorite) {

                    showRecipe($favorite['recipe_id']);


            ?>

                    <li class="list-unstyled d-flex justify-content-between mx-5 my-3"><a href="showRecipe.php?id=<?= $favorite['recipe_id'] ?>" class="text-decoration-none text-dark"><?= htmlspecialchars_decode(ucfirst($favorite['name'])) ?></a><a href="profil.php?favoris_php&action=delete&id=<?= $favorite['recipe_id'] ?>"><i class="bi bi-trash3"></i></a></li>

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

            $blacklists = allBlacklistRecipes($userId);

            if (empty($blacklists)) {

                echo $info = "<p>Vous n'avez aucune recette blacklistée.</p>";
            } else {

                foreach ($blacklists as $blacklist) {

                    showRecipe($blacklist['recipe_id']);


            ?>

                    <li class="list-unstyled d-flex justify-content-between mx-5 my-3"><a href="showRecipe.php?id=<?= $blacklist['recipe_id'] ?>" class="text-decoration-none text-dark"><?= htmlspecialchars_decode(ucfirst($blacklist['name'])) ?></a><a href="profil.php?favoris_php&action=delete&id=<?= $blacklist['recipe_id'] ?>"><i class="bi bi-trash3"></i></a></li>

            <?php
                }
            }
            ?>

        </ul>

    </div>

</section>