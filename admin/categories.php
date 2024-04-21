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

        $idCategory = $_GET['id'];
        $category = showCategory($idCategory);
    }
}

if (isset($_GET['action']) && isset($_GET['id'])) {
    if (!empty($_GET['action']) && $_GET['action'] == 'delete' && !empty($_GET['id'])) {

        $idCategory = $_GET['id'];
        $category = deleteCategory($idCategory);
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

        $categoryName = isset($_POST['name']) ? $_POST['name'] : null;

        if (strlen($categoryName) < 3 || preg_match('/[0-9]+/', $categoryName)) {
            $info = alert("Le nom de la catégorie n'est pas valide", "danger");
        }

        if (empty($info)) {
            $categoryName = strip_tags($categoryName);


            addCategory($categoryName);

            $info = alert('Catégorie ajoutée avec succès!', 'success');
        }
    }
}


$title = "Catégories";
// require_once "../inc/header.inc.php"


?>


<div class="row mt-5 justify-content-center">
    <div class="col-sm-12 col-md-5 m-5">
        <h2 class="text-center fw-bolder mb-5"><?= isset($category) ? 'Modifier une catégorie' : 'Ajouter une catégorie' ?></h2>

        <?php
        echo $info;
        ?>

        <form action="" method="post" class="">
            <div class="row">
                <div class="col-md-12 mb-5">
                    <label for="name">Nom de la catégorie</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?= $category['name'] ?? '' ?>">
                </div>
                <div class="row">
                    <button type="submit" class="btn btn-danger w-50 mx-auto fs-5 btnAdd"><?= isset($category) ? 'Modifier' : 'Ajouter' ?></button>
                </div>
            </div>
        </form>
    </div>


    <div class="col-sm-12 col-md-5 m-5" id="categoriesTable">
        <h2 class="text-center fw-bolder mb-5">Liste des catégories</h2>
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

                $categories = allCategories();

                foreach ($categories as $category) {


                ?>
                    <tr>
                        <td><?= $category['id'] ?></td>
                        <td><?= ucfirst($category['name']) ?></td>
                        <td class="text-center"><a href="?categories_php&action=delete&id=<?= $category['id'] ?>"><i class="bi bi-trash-fill"></i></td>
                        <td class="text-center"><a href="?categories_php&action=update&id=<?= $category['id'] ?>"><i class="bi bi-pen-fill"></i></a></td>
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