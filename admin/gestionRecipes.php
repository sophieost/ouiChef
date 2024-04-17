<?php

require_once "../inc/functions.inc.php";

if (!isset($_SESSION['user'])) {
    header("location:" . RACINE_SITE . "identification.php");
} else {
    if ($_SESSION['user']['role'] == 'user') {
        header("location:" . RACINE_SITE . "index.php");
    }
}

$categories = allCategories();
$ingredients = allIngredients();


if ($_GET['action'] == 'update') {
    $id = $_GET['id'];
    $recipe = showRecipe($id);

    $recipe_categories = showCategoriesRecipe($id);
    $recipe_ingredients = showIngredientsRecipe($id);
}





$info = '';


if (!empty($_POST)) {
    // debug($_POST);

    $verif = true;

    foreach ($_POST as $value) {

        if (empty(trim($value))) {
            $verif = false;
        }
    }

    if (!$verif) {
        $info = alert("Tous les champs sont requis", "danger");
    } else {

        // ************************************************************************

        $maxSize = 500000;
        $extensions = array('.jpg', '.jpeg', '.png');
        $extension = strrchr($_FILES['image']['name'], '.');
        if (!in_array($extension, $extensions)) {
            echo 'vous devez uploader un fichier de type jpeg, jpg ou png';
        }
        if ($_FILES['image']['size'] > $maxSize) {
            echo 'alert';
        }

        if (!isset($_POST['name']) || (strlen($_POST['name']) < 3 && trim($_POST['name'])) || !preg_match('/^[a-zA-Z0-9 ]*$/', $_POST['name'])) {


            $info .= alert("Le nom n'est pas valide", "danger");
        }


        if (!isset($_POST['slug']) || (strlen($_POST['slug']) < 3 && trim($_POST['slug'])) || !preg_match('/^[a-zA-Z0-9-]+$/', $_POST['slug'])) {


            $info .= alert("Le slug n'est pas valide", "danger");
        }

        if (!isset($_POST['instructions']) || strlen($_POST['instructions']) < 50) {

            $info .= alert("Les instructions ne sont pas valides", "danger");
        }

        if (!isset($_POST['repas'])) {

            $info .= alert("Le champs repas n'est pas valide", "danger");
        }


        if (!isset($_POST['plat'])) {

            $info .= alert("Le champs plat n'est pas valide", "danger");
        }


        if (!isset($_POST['season'])) {

            $info .= alert("Le champs saison n'est pas valide", "danger");
        }


        if (!isset($_POST['price'])) {

            $info .= alert("Le champs prix n'est pas valide", "danger");
        }


        if (!isset($_POST['time'])) {

            $info .= alert("Le champs temps de préparation n'est pas valide", "danger");
        }




        //S'il n y a pas d'erreur sur le formulaire
        if (empty($info)) {



            $name = htmlentities(trim($_POST['name']));
            $slug = htmlentities(trim($_POST['slug']));
            $image = $_FILES['image']['name'];
            $instructions = htmlentities(trim($_POST['instructions']));
            $repas = $_POST['repas'];
            $plat = $_POST['plat'];
            $season = $_POST['season'];
            $price = $_POST['price'];
            $time = $_POST['time'];
            $categories = $_POST['category'];
            $ingredients = $_POST['time'];
            $recipe_id = $_POST['recipe_id'];
            $ingredient_id = $_POST['ingredient_id'];
            $quantity = $_POST['quantity'];
            $unité = $_POST['unité'];


            if (isset($_GET['action']) && $_GET['action'] == 'update' && isset($_GET['id'])) {


                move_uploaded_file($_FILES['image']['tmp_name'], '../assets/img/' . $image);

                $result = updateRecipe($id, $name, $slug, $image, $instructions, $repas, $plat, $season, $price, $time, $categories, $ingredients);
            } else {

                move_uploaded_file($_FILES['image']['tmp_name'], '../assets/img/' . $image);

                // On enregistre le fichier image qui se trouve à l'adresse contenue dans $_FILES['image']['tmp_name'] vers la destination qui est le dossier "img" à l'adresse "../assets/nom_du_fichier.jpg".

                $result = addRecipe($name, $slug, $image, $instructions, $repas, $plat, $season, $price, $time, $categories, $ingredients);
            }

            header('location:dashboard.php?recipes_php');
        }
    }
}


$title = 'Gestion des recettes';

require_once "../inc/header.inc.php";

?>

<main class="py-5" id="gestionRecipes">


    <h2 class="text-center fw-bolder mb-5"><?= isset($recipe) ? 'Modifier une recette' : 'Ajouter une recette' ?></h2>
    <?php
    echo $info;
    ?>
    <form action="" method="post" enctype="multipart/form-data" class="container" id="recipeForm">

        <div class="row mt-5">
            <div class="col-md-4 mb-5">
                <label for="name" class="fw-bolder fs-5">Nom</label>
                <input type="hidden" name="id" value="<?= $recipe['id'] ?? '' ?>">
                <input type="text" id="name" name="name" class="form-control" value="<?= $recipe['name'] ?? '' ?>">
            </div>
            <div class="col-md-4 mb-5">
                <label for="slug" class="fw-bolder fs-5">Slug</label>
                <input type="text" id="slug" name="slug" class="form-control" value="<?= $recipe['slug'] ?? '' ?>">
            </div>
            <div class="col-md-4 mb-5">
                <label for="image" class="fw-bolder fs-5">Photo</label>
                <?= isset($recipe['image']) ? "<strong> ancienne </strong>" . $recipe['image'] : "" ?>
                <input class="form-control" type="file" id="image" name="image" value="<?= $recipe['image'] ?? '' ?>">
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <label for="instructions" class="fw-bolder fs-5">Instructions</label>
                <textarea name="instructions" id="instructions" cols="30" rows="10" placeholder="Séparer les étapes par un /" class="form-control"><?= $recipe['instructions'] ?? '' ?></textarea>
            </div>
        </div>

        <div class="row mt-5 ms-3">
            <label class="fw-bolder fs-5">Catégories</label>

            <?php

            $recipe_categories_ids = array_column($recipe_categories ?? [], 'category_id');


            foreach ($categories as $category) {

            ?>
                <div class="form-check col-sm-12 col-md-4 mx-auto">
                    <input type="checkbox" id="category-<?= $category['id'] ?>" name="categories[]" value="<?= $category['id'] ?>" <?= in_array($category['id'], $recipe_categories_ids ?? []) ? 'checked' : '' ?>>

                    <label for="category-<?= $category['id'] ?>"><?= $category['name'] ?></label>
                </div>
            <?php
            }
            ?>
        </div>

        <div class="row mt-5 ms-3" id="ingredients">
            <label for="ingredient-0" class="fw-bolder fs-5">Ingrédients</label>

            <?php

            foreach ($ingredients as $ingredient) {

            ?>
                <div class="row">
                    <div class="col-4">
                        <input type="checkbox" class="form-check-input mb-3" name="ingredients[<?= $ingredient['id'] ?>][checked]" <?= array_key_exists($ingredient['id'], $recipe_ingredients ?? []) ? 'checked' : '' ?>> <?= $ingredient['name'] ?>
                    </div>

                    <div class="col-4">
                        <input type="number" class="form-control mb-3" name="ingredients[<?= $ingredient['id'] ?>][quantity]" placeholder="Quantité" value="<?= $recipe_ingredients[$ingredient['id']]['quantity'] ?? '' ?>">
                    </div>

                    <div class="col-4">
                        <input type="text" class="form-control mb-3" name="ingredients[<?= $ingredient['id'] ?>][unité]" placeholder="Unité" value="<?= $recipe_ingredients[$ingredient['id']]['unité'] ?? '' ?>">
                    </div>
                </div>

                <hr>

            <?php
            }
            ?>

        </div>


        <div class="row mt-5 ms-3">

            <div class="col-md-4">
                <label for="repas" class="fw-bolder fs-5">Repas</label>

                <div class="form-check col-sm-6 col-md-4">
                    <input type="checkbox" name="repas" class="form-check-input" id="flexRadioDefault1" value="'déjeuner' <?php if (isset($recipe['repas']) && $recipe['repas'] == "déjeuner") echo 'selected' ?>">

                    <label class="form-check-label" for="flexRadioDefault1">Déjeuner</label>
                </div>

                <div class="form-check col-sm-6 col-md-4">
                    <input type="checkbox" name="repas" class="form-check-input" id="flexRadioDefault1" value="'déjeuner' <?php if (isset($recipe['repas']) && $recipe['repas'] == "dîner") echo 'selected' ?>">

                    <label class="form-check-label" for="flexRadioDefault1">Dîner</label>
                </div>
            </div>
            <div class="col-md-4">
                <label for="plat" class="fw-bolder fs-5">Plat</label>

                <div class="form-check col-sm-4 col-md-4">
                    <input type="checkbox" name="plat" class="form-check-input" id="flexRadioDefault1" value="'entree' <?php if (isset($recipe['plat']) && $recipe['plat'] == "entree") echo 'selected' ?>">

                    <label class="form-check-label" for="flexRadioDefault1">Entrée</label>
                </div>

                <div class="form-check col-sm-4 col-md-4">
                    <input type="checkbox" name="plat" class="form-check-input" id="flexRadioDefault1" value="'plat' <?php if (isset($recipe['plat']) && $recipe['plat'] == "plat") echo 'selected' ?>">

                    <label class="form-check-label" for="flexRadioDefault1">Plat</label>
                </div>

                <div class="form-check col-sm-4 col-md-4">
                    <input type="checkbox" name="plat" class="form-check-input" id="flexRadioDefault1" value="'dessert' <?php if (isset($recipe['plat']) && $recipe['plat'] == "dessert") echo 'selected' ?>">

                    <label class="form-check-label" for="flexRadioDefault1">Dessert</label>
                </div>
            </div>
            <div class="col-md-4">
                <label for="season" class="fw-bold fs-5">Saison</label>

                <div class="form-check col-sm-4 col-md-4">
                    <input type="checkbox" name="season" class="form-check-input" id="flexRadioDefault1" value="'hiver' <?php if (isset($recipe['season']) && $recipe['season'] == "hiver") echo 'selected' ?>">

                    <label class="form-check-label" for="flexRadioDefault1">Hiver</label>
                </div>

                <div class="form-check col-sm-4 col-md-4">
                    <input type="checkbox" name="season" class="form-check-input" id="flexRadioDefault1" value="'été' <?php if (isset($recipe['season']) && $recipe['season'] == "été") echo 'selected' ?>">

                    <label class="form-check-label" for="flexRadioDefault1">Eté</label>
                </div>

            </div>

        </div>



        <div class="row mt-5">
            <div class="mb-3 col-md-6">
                <label for="time" class="form-label  fs-5 fw-bold">Temps de péparation</label>
                <select multiple name="time" id="time" class="form-select form-select">
                    <option value="rapide" <?php if (isset($recipe['time']) && $recipe['time'] == "rapide") echo 'selected' ?>>Rapide</option>
                    <option value="moyen" <?php if (isset($recipe['time']) && $recipe['time'] == "moyen") echo 'selected' ?>>Moyen</option>
                    <option value="long" <?php if (isset($recipe['time']) && $recipe['time'] == "long") echo 'selected' ?>>Long</option>

                </select>
            </div>
            <div class="mb-3 col-md-6">
                <label for="price" class="form-label  fs-5 fw-bold">Prix</label>
                <select multiple name="price" id="price" class="form-select form-select">
                    <option value="bon marché" <?php if (isset($recipe['price']) && $recipe['price'] == "bon marché") echo 'selected' ?>>Bon marché</option>
                    <option value="raisonnable" <?php if (isset($recipe['price']) && $recipe['price'] == "raisonnable") echo 'selected' ?>>Raisonnable</option>
                    <option value="cher" <?php if (isset($recipe['price']) && $recipe['price'] == "cher") echo 'selected' ?>>Cher</option>

                </select>
            </div>
        </div>


        <div class="row">
            <button type="submit" class="btn p-3 btnAdd w-50 mx-auto my-5"><?= isset($recipe) ? 'Modifier' : 'Ajouter' ?></button>
        </div>


    </form>


</main>




<?php

require_once "../inc/footer.inc.php";
?>