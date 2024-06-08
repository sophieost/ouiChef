<?php

require_once "inc/functions.inc.php";


if (!empty($_GET)) {

    $id = $_GET['id'];

    $recipes = showRecipe($id);

    if ($_GET['id'] != $recipes['id']) {

        header("location:" . RACINE_SITE . "index.php");

    } else {

        $instructions = stringToArray($recipes['instructions']);
    }
}

$menu_id = isset($_GET['id_menu']) ? $_GET['id_menu'] : null;


if (isset($_GET['action']) && isset($_GET['id'])) {
    if (!empty($_GET['action']) && $_GET['action'] == 'fav' && !empty($_GET['id'])) {
        $userId = $_SESSION['user']['id'];
        $recipeId = $_GET['id'];

        addRecipeToFavorites($userId, $recipeId);
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ingredients'])) {

    $userId = $_SESSION['user']['id'];

    $ingredients = $_POST['ingredients'];

    foreach ($ingredients as $ingredient) {

        if (isset($ingredient['checked']) && $ingredient['checked'] == 1) {
            $name = $ingredient['name'];
            $quantity = $ingredient['quantity'] ?? '';
            $unity = $ingredient['unity'] ?? '';

            addIngredientToList($userId, $name, $quantity, $unity);
        }
    }
}


$title = "Recette Détaillée - Savourez Chaque Étape de Votre Plat Préféré";

$metadescription = "Plongez dans les détails de nos recettes exclusives. Suivez des instructions étape par étape pour créer des plats délicieux qui impressionneront à coup sûr vos invités et raviront vos papilles.";

require_once "inc/header.inc.php";


$info = '';

?>


<section id="showRecipe" class=" bg-white p-5 border border-3 rounded-5 mt-5">
    <?php echo $info; ?>

    <div class="d-flex justify-content-between align-items-center py-3 container">
        <h1><?= $recipes['name'] ?></h1>

        <ul class="list-unstyled mt-3 d-flex justify-content-around infosCard">

            <?php if (isset($recipes['price'])) : ?>
                <li class="px-3">

                    <?php
                    switch ($recipes['price']) {
                        case 'bonMarche':
                            echo 'Bon marché <i class="bi bi-currency-euro fs-4"></i>';
                            break;
                        case 'raisonnable':
                            echo 'Raisonnable <i class="bi bi-currency-euro fs-4"></i><i class="bi bi-currency-euro fs-4"></i>';
                            break;
                        case 'cher':
                            echo 'Cher <i class="bi bi-currency-euro fs-4"></i><i class="bi bi-currency-euro fs-4"></i><i class="bi bi-currency-euro fs-4"></i>';
                            break;
                    }
                    ?>
                </li>
            <?php endif; ?>

            <?php if (isset($recipes['time'])) : ?>
                <li class="px-3">
                    <?= ucfirst($recipes['time']) ?>
                    <?php
                    switch ($recipes['time']) {
                        case 'rapide':
                            echo '<i class="bi bi-clock fs-4"></i>';
                            break;
                        case 'moyen':
                            echo '<i class="bi bi-clock fs-4"></i><i class="bi bi-clock fs-4"></i>';
                            break;
                        case 'long':
                            echo '<i class="bi bi-clock fs-4"></i><i class="bi bi-clock fs-4"></i><i class="bi bi-clock fs-4"></i>';
                            break;
                    }
                    ?>
                </li>
            <?php endif; ?>

            <li>

                <?php
                $userId = $_SESSION['user']['id'];
                $isFavorite = isRecipeFavorite($userId, $id);
                ?>

                <span>Ajouter aux favoris</span>
                <a href="<?= RACINE_SITE ?>showRecipe.php?action=fav&id=<?= $recipes['id'] ?>" class="linkFav"><i class="bi <?= $isFavorite ? 'bi-heart-fill' : 'bi-heart' ?> fs-4 iconFav <?= $isFavorite ? 'text-danger' : 'text-dark' ?>"></i>
                </a>
            </li>
        </ul>

    </div>

    <div class="row border-top border-2 py-4 container mx-auto">
        <div class="col-lg-6 col-md-12 mb-3">
            <img src="<?= RACINE_SITE . "assets/img/" . $recipes['image'] ?>" class="card-img" alt="image de . <?= $recipes['name'] ?>">
        </div>

        <div class="ingredients col-lg-6 col-md-12">


            <form action="" method="post" class="ingredientsRecipe">
                <div class="d-flex justify-content-between align-items-center titleResponsive">

                    <?php
                    $infosMenu = getMenuInfoById($menu_id);
                    if (isset($_SESSION['user'])) {
                        $nb_pers = $infosMenu['nb_pers'];
                    } else {
                        $nb_pers = 2;
                    }

                    ?>
                    <h3 class="mb-3 mx-5">INGREDIENTS</h3>
                    <h5>Pour <?= $nb_pers ?> personne(s)</h5>
                </div>


                <div class="d-flex flex-column mx-5">

                    <?php
                    $ingredients =  showIngredientsRecipe($id);

                    foreach ($ingredients as $index => $ingredient) {
                        $quantite_ajustee = $ingredient['quantity'] * $nb_pers;
                    ?>
                        <div class="row my-3 justify-content-between">

                            <div class="col">
                                <input class="form-check-input ingredient-checkbox bg-transparent" type="checkbox" name="ingredients[<?= $index ?>][checked]" value="1">
                                <input type="hidden" name="ingredients[<?= $index ?>][name]" value="<?= $ingredient['ingredient'] ?>">
                                <label class="form-check-label ms-3" for="ingredient">
                                    <?= $ingredient['ingredient'] ?>
                                </label>
                            </div>

                            <div class="col">
                                <div class="d-flex justify-content-center align-items-center">
                                    <?php
                                    if ($ingredient['quantity'] == 0) {
                                        echo '';
                                    } else {
                                    ?>
                                        <input class="form-control border-0 text-end ingredient-checkbox" type="text" id="quantity" name="ingredients[<?= $index ?>][quantity]" value="<?= $quantite_ajustee ?>">
                                    <?php
                                    }
                                    if ($ingredient['unity'] == 0) {
                                        echo '';
                                    } else {
                                    ?>

                                        <input class="form-control p-0 border-0 ingredient-checkbox" type="text" id="unity" name="ingredients[<?= $index ?>][unity]" value="<?= $ingredient['unity'] ?>">
                                    <?php
                                    } ?>
                                </div>

                            </div>

                        </div>

                    <?php
                    }
                    ?>
                </div>
                <div class="d-flex justify-content-center my-5">

                    <button type="submit" class="btn p-2 border border-2 rounded-3 ingredientsAddBtn">Ajouter à la liste de courses</button>


                </div>
            </form>

            <div id="message-success" style="display: none;" class="alert alert-success" role="alert">
                La liste de courses a bien été mise à jour.
            </div>

        </div>
    </div>
    <div class="container">
        <h3 class="mb-3">INSTRUCTIONS</h3>
        <?php
        foreach ($instructions as $instruction) {
        ?>
            <p><?= $instruction ?></p>

        <?php
        }
        ?>
    </div>



</section>


<script>
    //   AJOUTER LES INGREDIENTS A LA LISTE DE COURSES

    // document.querySelector('.ingredientsRecipe').addEventListener('submit', function(e) {
    //     e.preventDefault();

    //     let shoppingList = [];
    //     let checkboxes = document.querySelectorAll('.ingredient-checkbox:checked');

    //     checkboxes.forEach(function(checkbox, index) {
    //         let ingredientRow = checkbox.closest('.row');
    //         let quantityInput = ingredientRow.querySelector('[name="quantity[]"]');
    //         let unitInput = ingredientRow.querySelector('[name="unity[]"]');

    //         if (checkbox.checked) {
    //             shoppingList.push({
    //                 name: checkbox.value,
    //                 quantity: quantityInput.value,
    //                 unit: unitInput.value
    //             });
    //         }
    //     });

    //     localStorage.setItem('shoppingList', JSON.stringify(shoppingList));
    // })
</script>



<?php
// debug($_POST);
// require_once "inc/footer.inc.php";


?>