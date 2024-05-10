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

    if (!empty($_GET['action']) && $_GET['action'] == 'edit' && !empty($_GET['id'])) {

        $id = $_GET['id'];
        $ingredient = showIngredient($id);
    }
}

if (isset($_GET['action']) && isset($_GET['id'])) {
    if (!empty($_GET['action']) && $_GET['action'] == 'delete' && !empty($_GET['id'])) {

        $id = $_GET['id'];
        $ingredient = deleteIngredient($id);
    }
}



$info = '';


if (!empty($_POST)) {
    // debug($_POST);
    // debug($_FILES);

    $verif = true;

    foreach ($_POST as $value) {

        if (empty(trim($value))) {
            $verif = false;
        }
    }

    if (!$verif) {

        $info = alert("Tous les champs sont requis", "danger");
    } else {

        $name = htmlentities(trim($_POST['name']));

        if (!isset($_POST['name']) || (strlen($_POST['name']) < 3 && trim($_POST['name']))) {

            $info .= alert("Le nom n'est pas valide", "danger");
        }

        if (empty($info)) {


            if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {

                updateIngredient($id, $name);
            } else {

                $message = addIngredient($name);
            }
        }
    }
}


$ingredients = allIngredients();

$title = "Ingrédients";
require_once "../inc/header.inc.php"


?>


<div class="mt-3 d-flex justify-content-center">

    <div class="w-50 m-5">

        <h2 class="text-center fw-bolder mb-5"><?= isset($ingredient) ? 'Modifier un ingrédient' : 'Ajouter un ingrédient' ?></h2>

        <form action="" method="post">
            
            <div class="row">
                <div class="col-md-12 mb-5">
                    <label for="name">Nom de l'ingrédient</label>
                    <input type="text" id="name" name="name" class="form-control" required value="<?= $ingredient['name'] ?? ''; ?>">
                </div>
                <div class="row">
                    <button type="submit" class="btn btn-danger w-50 mx-auto fs-5 btnAdd"><?= isset($ingredient) ? 'Modifier' : 'Ajouter' ?></button>
                </div>
            </div>
        </form>
    </div>

</div>

<div class="alert alert-danger w-50 mx-auto text-center"><?=$message?></div>

<div class="row container mx-auto mb-5">
    <?php
    // Calcul du nombre d'ingrédients par colonne
    $totalIngredients = count($ingredients);
    $ingredientsPerColumn = ceil($totalIngredients / 3);

    // Affichage des ingrédients sur trois colonnes
    for ($col = 0; $col < 3; $col++) {
    ?>
        <div class="col">
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
                    // Calcul des indices de début et de fin pour cette colonne
                    $start = $col * $ingredientsPerColumn;
                    $end = min(($col + 1) * $ingredientsPerColumn, $totalIngredients);

                    // Affichage des ingrédients pour cette colonne
                    for ($i = $start; $i < $end; $i++) {
                        $ingredient = $ingredients[$i];
                    ?>
                        <tr>
                            <td><?= $ingredient['id'] ?></td>
                            <td><?= ucfirst($ingredient['name']) ?></td>
                            <td class="text-center"><a href="?ingredients_php&action=delete&id=<?= $ingredient['id'] ?>"><i class="bi bi-trash3-fill text-danger"></i></a></td>
                            <td class="text-center"><a href="?ingredients_php&action=edit&id=<?= $ingredient['id'] ?>"><i class="bi bi-pen-fill"></i></a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } 
    
    // debug($_POST);
    ?>
</div>





<?php
require_once "../inc/footer.inc.php"
?>