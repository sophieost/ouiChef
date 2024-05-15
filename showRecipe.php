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

$info = '';



$title = "Recette";
require_once "inc/header.inc.php";



?>


<section id="showRecipe">

    <div class="container d-flex justify-content-between align-items-center my-5">
        <h2><?= $recipes['name'] ?></h2>

        <ul class="list-unstyled mt-3 d-flex jsutify-content-around infosCard">

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
                <span>Ajouter aux favoris</span>
                <a href="<?= RACINE_SITE ?>recette.php?action=add&id=<?= $recipes['id'] ?>" class="linkFav"><i class="bi bi-heart fs-4 iconFav text-dark"></i></a>
            </li>
        </ul>

    </div>

    <div class="row">
        <div class="col-md-6 col-sm-12 h-100 mb-3">
            <img src="<?= RACINE_SITE . "assets/img/" . $recipes['image'] ?>" class="card-img" alt="image de . <?= $recipes['name'] ?>">
        </div>

        <div class="ingredients col-md-6 col-sm-12">

            <h3 class="mb-3">INGREDIENTS</h3>

            <form action="" method="post">
                <div class="d-flex flex-column mx-5">

                    <?php
                    $ingredients =  showIngredientsRecipe($id);

                    foreach ($ingredients as $ingredient) {
                    ?>
                        <div class="d-flex my-3 justify-content-between">
                            <div>
                                <input class="form-check-input" type="checkbox" id="ingedient" name="ingredient" value="<?= $ingredient['ingredient'] ?>">
                                <label class="form-check-label" for="ingredient">
                                    <?= $ingredient['ingredient'] ?>
                                </label>
                            </div>


                        </div>

                    <?php
                    }
                    ?>
                </div>
                <div class="d-flex justify-content-center my-5">
                    <input class="btn" type="submit" value="Ajouter à la liste de courses">
                </div>

            </form>
        </div>
    </div>
    <div>
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





<?php
// require_once "inc/footer.inc.php";


?>