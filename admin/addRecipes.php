<?php

require_once "../inc/functions.inc.php";

if (!isset($_SESSION['user'])) {
    header("location:" . RACINE_SITE . "identification.php");
} else {
    if ($_SESSION['user']['role'] == 'user') {
        header("location:" . RACINE_SITE . "index.php");
    }
}



$info = '';


if (!empty($_POST)) {



    $verif = true;


    foreach ($_POST as $value) {




        if (!isset($_POST['name']) || (strlen($_POST['name']) < 3 && trim($_POST['name']))) {


            $info .= alert("Le nom n'est pas valide", "danger");
        }


        // if (!isset($_POST['slug']) || (strlen($_POST['slug']) < 3 && trim($_POST['slug'])) || !preg_match('/^[a-zA-Z0-9-]+$/', $_POST['slug'])) {


        //     $info .= alert("Le slug n'est pas valide", "danger");
        // }

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
            $price = $_POST['price'] ?? '';
            $time = $_POST['time'] ?? 'all';
            $categories = isset($_POST['categories']) ? array_values($_POST['categories']) : [];
            $ingredients = $_POST['ingredients'] ?? [];
            $recipeId = $_POST['id'] ?? '';


            move_uploaded_file($_FILES['image']['tmp_name'], '../assets/img/' . $image);

            addRecipe($name, $slug, $image, $instructions, $typePlat, $season, $price, $time, $categories, $ingredients);

        }
        
        header('location:dashboard.php?recipes_php');
        $info = alert('Recette ajoutée avec succès!', 'success');
    }
}


$title = 'Ajouter une recette';

require_once "../inc/header.inc.php";

?>

<main class="py-5 gestionRecipes">


    <h2 class="text-center fw-bolder mb-5">Ajouter une recette</h2>
    <?php
    echo $info;
    ?>
    <form action="addRecipes.php" method="post" enctype="multipart/form-data" class="container" id="recipeForm">

        <div class="row mt-5">
            <div class="col-md-4 mb-5">
                <label for="name" class="fw-bolder fs-5 mb-3">Nom ( requis )</label>
                <input type="text" id="name" name="name" class="form-control">
            </div>
            <div class="col-md-4 mb-5">
                <label for="slug" class="fw-bolder fs-5 mb-3">Slug ( requis )</label>
                <input type="text" id="slug" name="slug" class="form-control" placeholder="sans espaces">
            </div>
            <div class="col-md-4 mb-5">
                <label for="image" class="fw-bolder fs-5 mb-3">Photo ( requis )</label>
                <input class="form-control" type="file" id="image" name="image" required>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <label for="instructions" class="fw-bolder fs-5 mb-3">Instructions</label>
                <textarea name="instructions" id="instructions" cols="30" rows="10" placeholder="Séparer les étapes par un /" class="form-control"></textarea>
            </div>
        </div>

        <div class="row mt-5 ms-3">
            <label for="category-0" class="fw-bolder fs-5 mb-3">Catégories</label>

            <?php

            $categories = allCategories();

            foreach ($categories as $category) {

            ?>

                <div class="form-check col-sm-12 col-md-4 mx-auto">
                    <input type="checkbox" class="form-check-input" id="category-<?= $category['id'] ?>" name="categories[]" value="<?= $category['id'] ?>">
                    <label for="category-<?= $category['id'] ?>"><?= $category['name'] ?></label>
                </div>
            <?php
            }
            ?>
        </div>

        <div class="row mt-5 ms-3" id="ingredients">
            <label for="ingredient-0" class="fw-bolder fs-5 mb-3">Ingrédients ( requis )</label>

            <?php

            $ingredients = allIngredients();
            // debug($_POST);

            foreach ($ingredients as $ingredient) {


            ?>

                <div class="row">

                    <div class="col-4">
                        <input type="hidden" name="ingredients[<?= $ingredient['id'] ?>][id]" value="<?= $ingredient['id'] ?>">
                        <input type="checkbox" id="ingredient-<?= $ingredient['id'] ?>" class="form-check-input" name="ingredients[<?= $ingredient['id'] ?>][checked]">

                        <label for="ingredient-<?= $ingredient['id'] ?>"><?= $ingredient['name'] ?></label>
                    </div>


                    <div class="col-4">
                        <input type="number" class="form-control" name="ingredients[<?= $ingredient['id'] ?>][quantity]" placeholder="Quantité">
                    </div>

                    <div class="col-4">
                        <input type="text" class="form-control" name="ingredients[<?= $ingredient['id'] ?>][unity]" placeholder="Unité">
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
                    <input type="radio" name="typePlat" class="form-check-input" id="flexRadio3" value="entree">

                    <label class="form-check-label" for="flexRadio3">Entrée</label>
                </div>

                <div class="form-check col-sm-4 col-md-4">
                    <input type="radio" name="typePlat" class="form-check-input" id="flexRadio4" value="plat">

                    <label class="form-check-label" for="flexRadio4">Plat</label>
                </div>

                <div class="form-check col-sm-4 col-md-4">
                    <input type="radio" name="typePlat" class="form-check-input" id="flexRadio5" value="dessert">

                    <label class="form-check-label" for="flexRadio5">Dessert</label>
                </div>
            </div>

            <div class="col-md-6">
                <label for="season" class="fw-bold fs-5 mb-3">Saison</label>

                <div class="form-check col-sm-4 col-md-4">
                    <input type="radio" name="season" class="form-check-input" id="flexRadio6" value="hiver">

                    <label class="form-check-label" for="flexRadio6">Hiver</label>
                </div>

                <div class="form-check col-sm-4 col-md-4">
                    <input type="radio" name="season" class="form-check-input" id="flexRadio7" value="été">

                    <label class="form-check-label" for="flexRadio7">Eté</label>
                </div>

            </div>

        </div>


        <div class="row mt-5">
            <div class="mb-3 col-md-6">
                <label for="time" class="form-label  fs-5 fw-bold mb-3">Temps de péparation ( requis )</label>
                <select name="time" id="time" class="form-select">
                    <option value="rapide">Rapide</option>
                    <option value="moyen">Moyen</option>
                    <option value="long">Long</option>

                </select>
            </div>
            <div class="mb-3 col-md-6">
                <label for="price" class="form-label  fs-5 fw-bold mb-3">Prix ( requis )</label>
                <select name="price" id="price" class="form-select">
                    <option value="bonMarche">Bon marché</option>
                    <option value="raisonnable">Raisonnable</option>
                    <option value="cher">Cher</option>



                </select>
            </div>
        </div>


        <div class="row">
            <button type="submit" class="btn p-3 btnAdd w-50 mx-auto my-5">Ajouter</button>
        </div>


    </form>


</main>




<?php

// if (isset($_GET['recipes_php'])) {
//     require_once "recipes.php";
// }


require_once "../inc/footer.inc.php";
?>