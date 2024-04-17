<?php

require_once "../inc/functions.inc.php";

if (!isset($_SESSION['user'])) {
    header("location:" . RACINE_SITE . "identification.php");
} else {
    if ($_SESSION['user']['role'] == 'user') {
        header("location:" . RACINE_SITE . "index.php");
    }
}

// ********************************************************

if (isset($_GET['action']) && isset($_GET['id'])) {

    if (!empty($_GET['action']) && $_GET['action'] == 'update' && !empty($_GET['id'])) {

        $idIngredient = $_GET['id'];
        $ingredient = showIngredient($idIngredient);
    }
}

if (isset($_GET['action']) && isset($_GET['id'])) {
    if (!empty($_GET['action']) && $_GET['action'] == 'delete' && !empty($_GET['id'])) {

        $idIngredient = $_GET['id'];
        $ingredient = deleteIngredient($idIngredient);
    }
}
// ********************************************************



$info = '';
if (!empty($_POST)) {
    $verif = true;

    foreach ($_POST as $value) {

        if (empty($value)) {

            $verif = false;
        }
    }

    if (!$verif) {

        $info = alert("Veuillez renseigner tout les champs", "danger");
    } else {

        $ingredientName = isset($_POST['name']) ? $_POST['name'] : null;

        if (strlen($ingredientName) < 3 || preg_match('/[0-9]+/', $ingredientName)) {
            $info = alert("Le nom de l'ingrédient n'est pas valide", "danger");
        }

        if (empty($info)) {
            $ingredientName = strip_tags($ingredientName);


            addIngredient($ingredientName);

            $info = alert('Ingrédient ajouté avec succès!', 'success');
        }
    }
}


$title = "Ingrédients";
// require_once "../inc/header.inc.php"


?>


<div class="row mt-5 justify-content-center">
    <div class="col-sm-12 col-md-5 m-5">
        <h2 class="text-center fw-bolder mb-5"><?= isset($ingredient) ? 'Modifier un ingrédient' : 'Ajouter un ingrédient' ?></h2>

        <?php
        echo $info;
        ?>

        <form action="" method="post" class="">
            <div class="row">
                <div class="col-md-12 mb-5">
                    <label for="name">Nom de l'ingrédient</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?= $ingredient['name'] ?? '' ?>">
                </div>
                <div class="row">
                    <button type="submit" class="btn btn-danger w-50 mx-auto fs-5 btnAdd"><?= isset($ingredient) ? 'Modifier' : 'Ajouter' ?></button>
                </div>
            </div>
        </form>
    </div>


    <div class="col-sm-12 col-md-5 m-5" id="ingredientsTable">
        <h2 class="text-center fw-bolder mb-5">Liste des ingrédients</h2>
        <table class="table table-bordered mt-5">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Supprimer</th>
                    <th>Modifier</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $ingredients = allIngredients();

                foreach ($ingredients as $ingredient) {


                ?>
                    <tr>
                        <td><?= $ingredient['id'] ?></td>
                        <td><?= ucfirst($ingredient['name']) ?></td>
                        <td class="text-center"><a href="?ingredients_php&action=delete&id=<?= $ingredient['id'] ?>"><i class="bi bi-trash3-fill text-danger"></i></td>
                        <td class="text-center"><a href="?ingredients_php&action=update&id=<?= $ingredient['id'] ?>"><i class="bi bi-pen-fill"></i></a></td>
                    </tr>

                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>



<?php
require_once "../inc/footer.inc.php"
?>