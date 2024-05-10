<?php

require_once "../inc/functions.inc.php";


if (!isset($_SESSION['user'])) {
    header("location:" . RACINE_SITE . "identification.php");
} else {
    if ($_SESSION['user']['role'] == 'user') {
        header("location:" . RACINE_SITE . "index.php");
    }
}


if ($_GET['action'] == 'update') {

    $id = $_GET['id'];

    $recipe = showRecipe($id);
    showCategoriesRecipe($id);
    showIngredientsRecipe($id);
}


$info = '';


if (!empty($_POST)) {


    // debug($_POST);


    $verif = true;

    foreach ($_POST as $value) {


        if (!isset($_POST['name']) || (strlen($_POST['name']) < 3 && trim($_POST['name']))) {


            $info .= alert("Le nom n'est pas valide", "danger");
        }


        if (!isset($_POST['slug']) || (strlen($_POST['slug']) < 3 && trim($_POST['slug'])) || !preg_match('/^[a-zA-Z0-9-]+$/', $_POST['slug'])) {


            $info .= alert("Le slug n'est pas valide", "danger");
        }

        if (!isset($_POST['instructions']) || strlen($_POST['instructions']) < 50) {

            $info .= alert("Les instructions ne sont pas valides", "danger");
        }


        //S'il n y a pas d'erreur sur le formulaire
        if (empty($info)) {

            $name = htmlentities(trim($_POST['name']));
            $slug = htmlentities(trim($_POST['slug']));
            $image = $_FILES['image']['name'] ?? '';
            $instructions = htmlentities(trim($_POST['instructions']));
            $typePlat = $_POST['typePlat'] ?? '';
            $season = $_POST['season'] ?? 'all';
            $price = $_POST['price'] ?? 'all';
            $time = $_POST['time'] ?? '';
            $categories = isset($_POST['categories']) ? array_values($_POST['categories']) : [];
            $ingredients = $_POST['ingredients'] ?? [];
            $id = $_POST['id'] ?? '';




            // debug($_FILES);
            move_uploaded_file($_FILES['image']['tmp_name'], '../assets/img/' . $image);

            // debug($ingredients);

            updateRecipe($id, $name, $slug, $image, $instructions, $typePlat, $season, $price, $time, $categories, $ingredients);
        }
    }
    header('location:' . RACINE_SITE . 'admin/dashboard.php?recipes_php');
}


$title = 'Modifier une recette';

require_once "../inc/header.inc.php";

?>

<main class="py-5 gestionRecipes">


    <h2 class="text-center fw-bolder mb-5">Modifier une recette</h2>
    <?php
    echo $info;
    ?>
    <form action="updateRecipes.php" method="post" enctype="multipart/form-data" class="container" id="updateRecipeForm">

        <div class="row mt-5">
            <div class="col-md-4 mb-5">
                <label for="name" class="fw-bolder fs-5 mb-3">Nom ( requis )</label>
                <input type="hidden" name="id" value="<?= $recipe['id'] ?? '' ?>">
                <input type="text" id="name" name="name" class="form-control" value="<?= $recipe['name'] ?? '' ?>">
            </div>
            <div class="col-md-4 mb-5">
                <label for="slug" class="fw-bolder fs-5 mb-3">Slug ( requis )</label>
                <input type="text" id="slug" name="slug" class="form-control" placeholder="sans espaces" value="<?= $recipe['slug'] ?? '' ?>">
            </div>
            <div class="col-md-4 mb-5">
                <label for="image" class="fw-bolder fs-5 mb-3">Photo ( requis )</label>
                <?= isset($recipe['image']) ? "<strong> ancienne </strong>" . $recipe['image'] : "" ?>
                <input class="form-control" type="file" id="image" name="image" value="<?= $recipe['image'] ?? '' ?>">
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <label for="instructions" class="fw-bolder fs-5 mb-3">Instructions</label>
                <textarea name="instructions" id="instructions" cols="30" rows="10" placeholder="Séparer les étapes par un /" class="form-control"><?= $recipe['instructions'] ?? '' ?></textarea>
            </div>
        </div>

        <div class="row mt-5 ms-3">
            <label class="fw-bolder fs-5 mb-3">Catégories</label>

            <?php

            $categories = allCategories();
            $recipeCategories = showCategoriesRecipe($id);
            $linkedCategoryIds = array_column($recipeCategories, 'category_id');

            foreach ($categories as $category) {

                $isChecked = in_array($category['id'], $linkedCategoryIds);
            ?>

                <div class="form-check col-sm-12 col-md-4 mx-auto">
                    <input type="checkbox" class="form-check-input" id="category-<?= $category['id'] ?>" name="categories[]" value="<?= $category['id'] ?>" <?= $isChecked ? 'checked' : '' ?>>
                    <label for="category-<?= $category['id'] ?>"><?= $category['name'] ?></label>
                </div>
            <?php
            }
            ?>
        </div>

        <div class="row mt-5 ms-3" id="ingredients">
            <label for="ingredient-0" class="fw-bolder fs-5 mb-3">Ingrédients ( requis )</label>

            <?php

            $recipeIngredients = showIngredientsRecipe($id);
            $linkedIngredientIds = $linkedIngredients = [];

            foreach ($recipeIngredients as $ingredient) {
                $linkedIngredients[$ingredient['ingredient_id']] = $ingredient;
            }

            $ingredients = allIngredients();

            foreach ($ingredients as $ingredient) {

                $isChecked = array_key_exists($ingredient['id'], $linkedIngredients);
                $quantity = $isChecked ? $linkedIngredients[$ingredient['id']]['quantity'] : '';
                $unity = $isChecked ? $linkedIngredients[$ingredient['id']]['unity'] : '';


                // foreach ($ingredients as $ingredient) {

                //     $isChecked = isset($linkedIngredients[$ingredient['id']]);
                //     $quantity = $isChecked ? $linkedIngredients[$ingredient['id']]['quantity'] : '';
                //     $unity = $isChecked ? $linkedIngredients[$ingredient['id']]['unity'] : '';

            ?>

                <div class="row">

                    <div class="col-4">
                        <input type="checkbox" id="ingredient-<?= $ingredient['id'] ?>" class="form-check-input" name="ingredients[<?= $ingredient['id'] ?>][checked]" value="<?= $ingredient['id'] ?>" <?= $isChecked ? 'checked' : '' ?>>
                        <label for="ingredient-<?= $ingredient['id'] ?>"><?= $ingredient['name'] ?></label>
                    </div>


                    <div class="col-4">
                        <input type="number" class="form-control" name="ingredients[<?= $ingredient['id'] ?>][quantity]" placeholder="Quantité" value="<?= $quantity ?>">
                    </div>

                    <div class="col-4">
                        <input type="text" class="form-control" name="ingredients[<?= $ingredient['id'] ?>][unity]" placeholder="Unité" value="<?= $unity ?>">
                    </div>
                </div>

                <hr>

            <?php
            }
            // }
            ?>

        </div>


        <div class="row mt-5 ms-3">

            <div class="col-md-6">
                <label for="typePlat" class="fw-bolder fs-5 mb-3">Type de plat ( requis )</label>

                <div class="form-check col-sm-4 col-md-4">
                    <input type="radio" name="typePlat" class="form-check-input" id="flexRadioDefault1" value="entree" <?php if (isset($recipe['typePlat']) && $recipe['typePlat'] == "entree") echo 'checked' ?>>

                    <label class="form-check-label" for="flexRadioDefault1">Entrée</label>
                </div>

                <div class="form-check col-sm-4 col-md-4">
                    <input type="radio" name="typePlat" class="form-check-input" id="flexRadioDefault2" value="plat" <?php if (isset($recipe['typePlat']) && $recipe['typePlat'] == "plat") echo 'checked' ?>>

                    <label class="form-check-label" for="flexRadioDefault2">Plat</label>
                </div>

                <div class="form-check col-sm-4 col-md-4">
                    <input type="radio" name="typePlat" class="form-check-input" id="flexRadioDefault3" value="dessert" <?php if (isset($recipe['typePlat']) && $recipe['typePlat'] == "dessert") echo 'checked' ?>>

                    <label class="form-check-label" for="flexRadioDefault3">Dessert</label>
                </div>
            </div>
            <div class="col-md-6">
                <label for="season" class="fw-bold fs-5 mb-3">Saison</label>

                <div class="form-check col-sm-4 col-md-4">
                    <input type="checkbox" name="season" class="form-check-input" id="flexRadioDefault1" value="hiver" <?php if (isset($recipe['season']) && $recipe['season'] == "hiver") echo 'checked' ?>>

                    <label class="form-check-label" for="flexRadioDefault1">Hiver</label>
                </div>

                <div class="form-check col-sm-4 col-md-4">
                    <input type="checkbox" name="season" class="form-check-input" id="flexRadioDefault1" value="été" <?php if (isset($recipe['season']) && $recipe['season'] == "ete") echo 'checked' ?>>

                    <label class="form-check-label" for="flexRadioDefault1">Eté</label>
                </div>

            </div>

        </div>


        <div class="row mt-5">
            <div class="mb-3 col-md-6">
                <label for="time" class="form-label  fs-5 fw-bold mb-3">Temps de péparation ( requis )</label>
                <select name="time" id="time" class="form-select">
                    <option value="rapide" <?php if (isset($recipe['time']) && $recipe['time'] == "rapide") echo 'selected' ?>>Rapide</option>
                    <option value="moyen" <?php if (isset($recipe['time']) && $recipe['time'] == "moyen") echo 'selected' ?>>Moyen</option>
                    <option value="long" <?php if (isset($recipe['time']) && $recipe['time'] == "long") echo 'selected' ?>>Long</option>

                </select>
            </div>
            <div class="mb-3 col-md-6">
                <label for="price" class="form-label  fs-5 fw-bold mb-3">Prix ( requis )</label>
                <select name="price" id="price" class="form-select">
                    <option value="bonMarche" <?php if (isset($recipe['price']) && $recipe['price'] == "bonMarche") echo 'selected' ?>>Bon marché</option>
                    <option value="raisonnable" <?php if (isset($recipe['price']) && $recipe['price'] == "raisonnable") echo 'selected' ?>>Raisonnable</option>
                    <option value="cher" <?php if (isset($recipe['price']) && $recipe['price'] == "cher") echo 'selected' ?>>Cher</option>



                </select>
            </div>
        </div>


        <div class="row">
            <button type="submit" class="btn p-3 btnAdd w-50 mx-auto my-5">Modifier</button>
        </div>


    </form>


</main>




<?php

require_once "../inc/footer.inc.php";
?>