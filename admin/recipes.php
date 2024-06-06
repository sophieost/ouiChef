<?php

require_once "../inc/functions.inc.php";

if (!isset($_SESSION['user'])) {
    header("location:" . RACINE_SITE . "identification.php");
} else {
    if ($_SESSION['user']['role'] == 'user') {
        header("location:" . RACINE_SITE . "index.php");
    }
}

if (isset($_GET['action']) && isset($_GET['id'])) {
    if (!empty($_GET['action']) && $_GET['action'] == 'delete' && !empty($_GET['id'])) {

        $idRecipe = $_GET['id'];
        $recipe = deleteRecipe($idRecipe);
        header('location:dashboard.php?recipes_php');
    }
}


$metadescription = "Découvrez et gérez l'ensemble de votre collection culinaire avec notre liste complète des recettes. Mettez à jour, supprimez ou ajoutez de nouvelles créations directement depuis votre backoffice.";

$title = 'Liste des Recettes - Gestion Backoffice';

require_once "../inc/header.inc.php";

?>



<div class="d-flex flex-column m-auto mt-5 container" id="recipeTable">

    <h2 class="text-center fw-bolder mb-5 text-dark">Liste des recettes</h2>
    <a href="addRecipes.php" class="btn btn-sm p-3 fs-5 align-self-center btnAdd"> Ajouter une recette</a>

    <h3 class="container mt-5">Entrées</h3>
    <table id="entrees" class="table table-striped border-dark mb-5 container">
        <thead>
            <tr>
                <!-- th*7 -->
                <th scope="col">ID</th>
                <th scope="col">Nom</th>
                <th scope="col">Image</th>
                <th scope="col">Instructions</th>
                <th scope="col">Plat</th>
                <th scope="col">Saison</th>
                <th scope="col">Temps de préparation</th>
                <th scope="col">Prix</th>
                <th scope="col">Catégorie</th>
                <th scope="col">Ingrédients</th>
                <th scope="col">Supprimer</th>
                <th scope="col"> Modifier</th>
            </tr>
        </thead>

        <tbody class="table-group-divider">
            <?php

            $recipes = allRecipes();

            // foreach ($recipes as $recipe) {

                $entrees = [];
                $plats = [];
                $desserts = [];
            
                foreach ($recipes as $recipe) {
                    if ($recipe['typePlat'] == 'entree') {
                        $entrees[] = $recipe;
                    } elseif ($recipe['typePlat'] == 'plat') {
                        $plats[] = $recipe;
                    } elseif ($recipe['typePlat'] == 'dessert') {
                        $desserts[] = $recipe;
                    }
                }

                foreach ($entrees as $recipe) {

                $instructions = stringToArray($recipe['instructions']);


            ?>
                <tr>


                    <td><?= $recipe['id'] ?></td>

                    <th scope="row"><?= ucfirst($recipe['name']) ?></th>

                    <td> <img src=" <?= RACINE_SITE . "assets/img/" . $recipe['image'] ?>" alt="image de .<?= $recipe['name'] ?>" class="img-fluid "> </td>

                    <td><?= substr(ucfirst($recipe['instructions']), 0, 40) . "..."  ?></td>
                    <td><?= ucfirst($recipe['typePlat']) ?></td>
                    <td><?= isset($recipe['season']) ? ucfirst($recipe['season']) : "" ?></td>
                    <td><?= ucfirst($recipe['time']) ?></td>
                    <td> <?php if (isset($recipe['price']) && $recipe['price'] == 'bonMarche') {
                                echo 'Bon marché';
                            } else if (isset($recipe['price']) && $recipe['price'] == 'raisonnable') {
                                echo 'Raisonnable';
                            } else if (isset($recipe['price']) && $recipe['price'] == 'cher') {
                                echo 'Cher';
                            }
                            ?></td>

                    <td><?= isset($recipe['categories']) ? ucfirst($recipe['categories']) : "" ?></td>

                    <td><?= isset($recipe['ingredients']) ? substr(ucfirst($recipe['ingredients']), 0, 40)."..." : "" ?></td>

                    <td class="text-center"><a href="recipes.php?action=delete&id=<?= $recipe['id'] ?>"><i class="bi bi-trash-fill"></i></a></td>

                    <td class="text-center"><a href="updateRecipes.php?action=update&id=<?= $recipe['id'] ?>"><i class="bi bi-pen-fill"></i></a></td>

                </tr>
            <?php
            }
            ?>

        </tbody>


    </table>


    
    <h3 class="container">Plats</h3>
    <table id="plats" class="table table-striped border-dark mb-5 container">
        <thead>
            <tr>
                <!-- th*7 -->
                <th scope="col">ID</th>
                <th scope="col">Nom</th>
                <th scope="col">Image</th>
                <th scope="col">Instructions</th>
                <th scope="col">Plat</th>
                <th scope="col">Saison</th>
                <th scope="col">Temps de préparation</th>
                <th scope="col">Prix</th>
                <th scope="col">Catégorie</th>
                <th scope="col">Ingrédients</th>
                <th scope="col">Supprimer</th>
                <th scope="col"> Modifier</th>
            </tr>
        </thead>

        <tbody class="table-group-divider">
            <?php

            $recipes = allRecipes();

            // foreach ($recipes as $recipe) {

                $entrees = [];
                $plats = [];
                $desserts = [];
            
                foreach ($recipes as $recipe) {
                    if ($recipe['typePlat'] == 'entree') {
                        $entrees[] = $recipe;
                    } elseif ($recipe['typePlat'] == 'plat') {
                        $plats[] = $recipe;
                    } elseif ($recipe['typePlat'] == 'dessert') {
                        $desserts[] = $recipe;
                    }
                }

                foreach ($plats as $recipe) {

                $instructions = stringToArray($recipe['instructions']);


            ?>
                <tr>


                    <td><?= $recipe['id'] ?></td>

                    <th scope="row"><?= ucfirst($recipe['name']) ?></th>

                    <td> <img src=" <?= RACINE_SITE . "assets/img/" . $recipe['image'] ?>" alt="image de .<?= $recipe['name'] ?>" class="img-fluid "> </td>

                    <td><?= substr(ucfirst($recipe['instructions']), 0, 40) . "..."  ?></td>
                    <td><?= ucfirst($recipe['typePlat']) ?></td>
                    <td><?= isset($recipe['season']) ? ucfirst($recipe['season']) : "" ?></td>
                    <td><?= ucfirst($recipe['time']) ?></td>
                    <td> <?php if (isset($recipe['price']) && $recipe['price'] == 'bonMarche') {
                                echo 'Bon marché';
                            } else if (isset($recipe['price']) && $recipe['price'] == 'raisonnable') {
                                echo 'Raisonnable';
                            } else if (isset($recipe['price']) && $recipe['price'] == 'cher') {
                                echo 'Cher';
                            }
                            ?></td>

                    <td><?= isset($recipe['categories']) ? ucfirst($recipe['categories']) : "" ?></td>

                    <td><?= isset($recipe['ingredients']) ? substr(ucfirst($recipe['ingredients']), 0, 40)."..." : "" ?></td>

                    <td class="text-center"><a href="recipes.php?action=delete&id=<?= $recipe['id'] ?>"><i class="bi bi-trash-fill"></i></a></td>

                    <td class="text-center"><a href="updateRecipes.php?action=update&id=<?= $recipe['id'] ?>"><i class="bi bi-pen-fill"></i></a></td>

                </tr>
            <?php
            }
            ?>

        </tbody>
    </table>


    <h3 class="container">Desserts</h3>
    <table id="desserts" class="table table-striped border-dark mb-5 container">
        <thead>
            <tr>
                <!-- th*7 -->
                <th scope="col">ID</th>
                <th scope="col">Nom</th>
                <th scope="col">Image</th>
                <th scope="col">Instructions</th>
                <th scope="col">Plat</th>
                <th scope="col">Saison</th>
                <th scope="col">Temps de préparation</th>
                <th scope="col">Prix</th>
                <th scope="col">Catégorie</th>
                <th scope="col">Ingrédients</th>
                <th scope="col">Supprimer</th>
                <th scope="col"> Modifier</th>
            </tr>
        </thead>

        <tbody class="table-group-divider">
            <?php

            $recipes = allRecipes();

            // foreach ($recipes as $recipe) {

                $entrees = [];
                $plats = [];
                $desserts = [];
            
                foreach ($recipes as $recipe) {
                    if ($recipe['typePlat'] == 'entree') {
                        $entrees[] = $recipe;
                    } elseif ($recipe['typePlat'] == 'plat') {
                        $plats[] = $recipe;
                    } elseif ($recipe['typePlat'] == 'dessert') {
                        $desserts[] = $recipe;
                    }
                }

                foreach ($desserts as $recipe) {

                $instructions = stringToArray($recipe['instructions']);


            ?>
                <tr>


                    <td><?= $recipe['id'] ?></td>

                    <th scope="row"><?= ucfirst($recipe['name']) ?></th>

                    <td> <img src=" <?= RACINE_SITE . "assets/img/" . $recipe['image'] ?>" alt="image de .<?= $recipe['name'] ?>" class="img-fluid "> </td>

                    <td><?= substr(ucfirst($recipe['instructions']), 0, 40) . "..."  ?></td>
                    <td><?= ucfirst($recipe['typePlat']) ?></td>
                    <td><?= isset($recipe['season']) ? ucfirst($recipe['season']) : "" ?></td>
                    <td><?= ucfirst($recipe['time']) ?></td>
                    <td> <?php if (isset($recipe['price']) && $recipe['price'] == 'bonMarche') {
                                echo 'Bon marché';
                            } else if (isset($recipe['price']) && $recipe['price'] == 'raisonnable') {
                                echo 'Raisonnable';
                            } else if (isset($recipe['price']) && $recipe['price'] == 'cher') {
                                echo 'Cher';
                            }
                            ?></td>

                    <td><?= isset($recipe['categories']) ? ucfirst($recipe['categories']) : "" ?></td>

                    <td><?= isset($recipe['ingredients']) ? substr(ucfirst($recipe['ingredients']), 0, 40)."..." : "" ?></td>

                    <td class="text-center"><a href="recipes.php?action=delete&id=<?= $recipe['id'] ?>"><i class="bi bi-trash-fill"></i></a></td>

                    <td class="text-center"><a href="updateRecipes.php?action=update&id=<?= $recipe['id'] ?>"><i class="bi bi-pen-fill"></i></a></td>

                </tr>
            <?php
            }
            ?>

        </tbody>


    </table>


</div>



<?php
require_once "../inc/footer.inc.php";
?>