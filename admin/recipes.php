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
    }
}



$title = "Recettes";

?>



    <div class="d-flex flex-column m-auto mt-5 container" id="recipeTable">

        <h2 class="text-center fw-bolder mb-5 text-dark">Liste des recettes</h2>
        <a href="gestionRecipes.php" class="btn btn-sm p-3 fs-5 align-self-center btnAdd"> Ajouter une recette</a>
        <table class="table table-striped border-dark mt-5 container">
            <thead>
                <tr>
                    <!-- th*7 -->
                    <th scope="col">ID</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Image</th>
                    <th scope="col">Instructions</th>
                    <th scope="col">Repas</th>
                    <th scope="col">Plat</th>
                    <th scope="col">Saison</th>
                    <th scope="col">Prix</th>
                    <th scope="col">Temps de préparation</th>
                    <th scope="col">Catégorie</th>
                    <th scope="col">Ingrédients</th>
                    <th scope="col">Supprimer</th>
                    <th scope="col"> Modifier</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                <?php

                $recipes = allRecipes();

                foreach ($recipes as $recipe) {

                    $instructions = stringToArray($recipe['instructions']);


                ?>
                    <tr>


                        <td><?= $recipe['id'] ?></td>
                        <th scope="row"><?= ucfirst($recipe['name']) ?></th>

                        <td> <img src=" <?= RACINE_SITE . "assets/img/" . $recipe['image'] ?> " alt="image de .<?=$recipe['name']?>" class="img-fluid"> </td>

                        <td><?= substr(ucfirst($recipe['instructions']), 0, 40) . "..."  ?></td>
                        <td><?= isset($recipe['repas']) ? ucfirst($recipe['repas']) : "" ?></td>
                        <td><?= ucfirst($recipe['typePlat']) ?></td>
                        <td><?= isset($recipe['season']) ? ucfirst($recipe['season']) : "" ?></td>
                        <td><?= ucfirst(isset($recipe['price'])) ?></td>
                        <td><?= ucfirst($recipe['time']) ?></td>

                        <td><?= isset($recipe['categories']) ? ucfirst($recipe['categories']) : "" ?></td>

                        <td><?= isset($recipe['ingredients']) ? ucfirst($recipe['ingredients']) : "" ?></td>
                        
                        <td class="text-center"><a href="recipes.php?action=delete&id=<?= $recipe['id'] ?>"><i class="bi bi-trash-fill"></i></a></td>

                        <td class="text-center"><a href="gestionRecipes.php?action=update&id=<?= $recipe['id'] ?>"><i class="bi bi-pen-fill"></i></a></td>

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