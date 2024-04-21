<?php

require_once "../inc/functions.inc.php";




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

    $verif = true;

    foreach ($_POST as $value) {

        if (empty(trim($value))) {
            $verif = false;
        }
    }

    if (!$verif) {
        $info = alert("Tous les champs sont requis", "danger");
    } else {

        $maxSize = 500000;
        $extensions = array('.jpg', '.jpeg', '.png');
        $extension = strrchr($_FILES['image']['name'], '.');
        if (!in_array($extension, $extensions)) {
            echo 'vous devez uploader un fichier de type jpeg, jpg ou png';
        }
        if ($_FILES['image']['size'] > $maxSize) {
            echo 'alert';
        }


        if (!isset($_POST['name']) || (strlen($_POST['name']) < 3 && trim($_POST['name'])) || preg_match('/[0-9]+/', $_POST['name'])) {

            $info .= alert("Le nom n'est pas valide", "danger");
        }

        if (empty($info)) {

            $name = htmlentities(trim($_POST['name']));
            $image = $_FILES['image']['name'];


            if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {


                move_uploaded_file($_FILES['image']['tmp_name'], '../assets/img/ingredients/' . $image);

                $result = updateIngredient($id, $name, $image);
            } else {

                move_uploaded_file($_FILES['image']['tmp_name'], '../assets/img/ingredients/' . $image);

                $result = addIngredient($ingredientName, $image);
            }
        }
    }
}
$ingredients = allIngredients();

$title = "Ingrédients";
require_once "../inc/header.inc.php"


?>


<div class="row mt-5 justify-content-center">
    <div class="col-sm-12 col-md-5 m-5">
        <h2 class="text-center fw-bolder mb-5"><?= isset($ingredient) ? 'Modifier un ingrédient' : 'Ajouter un ingrédient' ?></h2>


        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $ingredient['id'] ?? ''; ?>">
            <div class="row">
                <div class="col-md-12 mb-5">
                    <label for="name">Nom de l'ingrédient</label>
                    <input type="text" id="name" name="name" class="form-control" required value="<?= $ingredient['name'] ?? ''; ?>">
                </div>
                <div class="col-md-12 mb-5">

                    <?php if (!empty($_POST['image'])) : ?>
                        <img src="<?php echo $ingredient['image']; ?>" alt="Image actuelle" width="100"><br>
                    <?php endif; ?>

                    <label for="image">Image de l'ingrédient</label>
                    <input class="form-control" type="file" id="image" name="image">
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
                    <th>Image</th>
                    <th>Supprimer</th>
                    <th>Modifier</th>
                </tr>
            </thead>
            <tbody>
                <?php


                foreach ($ingredients as $ingredient) {


                ?>
                    <tr>
                        <td><?= $ingredient['id'] ?></td>
                        <td><?= ucfirst($ingredient['name']) ?></td>
                        <td><img src="<?= RACINE_SITE . "assets/img/ingredients/" . $ingredient['image'] ?>" alt="image de.<?= $ingredient['name'] ?>" class="object-fit-cover w-25"></td>


                        <td class="text-center"><a href="?ingredients_php&action=delete&id=<?= $ingredient['id'] ?>"><i class="bi bi-trash3-fill text-danger"></i></td>

                        <td class="text-center"><a href="?ingredients_php&action=edit&id=<?= $ingredient['id'] ?>"><i class="bi bi-pen-fill"></i></a></td>
                    </tr>

                <?php

                    // debug($_FILES);
                }
                ?>
            </tbody>
        </table>
    </div>
</div>



<?php
require_once "../inc/footer.inc.php"
?>