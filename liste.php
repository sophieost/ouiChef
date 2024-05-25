<?php
require_once "inc/functions.inc.php";

$title = "Ma liste de courses";

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $filteredIngredients = array_filter($_POST['ingredients'], function ($ingredient) {
//         return isset($ingredient['checked']);
//     });

//     foreach ($filteredIngredients as $ingredient) {
//         $name = $ingredient["name"]; // Corrigez cette ligne pour utiliser la clé 'checked'
//         $quantity = $ingredient['quantity']; // Quantité
//         $unit = $ingredient['unit']; // Unité

//         // Stockez ces valeurs dans localStorage et affichez-les
//         echo "<script>
//             localStorage.setItem('shoppingList', JSON.stringify(" . json_encode($filteredIngredients) . "));
//         </script>";
//     }
// }


if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $userId = $_SESSION['user']['id'];
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : '';
    $unity = isset($_POST['unity']) ? $_POST['unity'] : '';

    addIngredientToList($userId, $name, $quantity, $unity);
}


if (isset($_GET['id'])) {
    $ingredientId = $_GET['id'];
    deleteIngredientFromList($ingredientId);
}


require_once "inc/header.inc.php";
?>

<div id="containerList">

    <h1>Ma liste de course</h1>
    <form action="" method="post" id="formList">
        <div class="d-flex">
            <input type="text" placeholder="Ajouter un article à la liste" name="name" class="my-auto">
            <input type="text" placeholder="Ajouter un article à la liste" name="quantity" hidden>
            <input type="text" placeholder="Ajouter un article à la liste" name="unity" hidden>

            <button type="submit" class="btn"><i class="bi bi-plus-circle fs-1 ms-3" id="add-ingredient" class="btn btnList"></i></button>
        </div>
    </form>

    <ul id="shopping-list">
        <?php
        $ingredientsList = showList();
        foreach ($ingredientsList as $ingredientList) {
        ?>
            <div class="d-flex align-items-center">
                <i class="bi bi-square me-3 p-2 fs-2 checklist-item"></i>
                <li><?= htmlspecialchars($ingredientList['quantity'] ?? '') . ' ' . htmlspecialchars($ingredientList['unity'] ?? '') . ' ' . htmlspecialchars($ingredientList['name'] ?? '') ?></li>
                <a href="liste.php?id=<?= $ingredientList['id'] ?>"><i class="bi bi-x-lg ms-3 p-2 fs-3"></i></a>
            </div>
        <?php
        }
        ?>
    </ul>
</div>



<script>

    document.addEventListener('DOMContentLoaded', function() {
        let checklistItems = document.querySelectorAll('.checklist-item');
        checklistItems.forEach(function(checkListItem) {
            checkListItem.addEventListener('click', function() {
                // Utilisez 'classList.toggle' pour basculer entre les classes
                this.classList.toggle('bi-square');
                this.classList.toggle('bi-check2-square');
            });
        });
    });



    // document.addEventListener('DOMContentLoaded', function() {
    //     displayShoppingList();



    //     document.getElementById('shopping-list').addEventListener('click', function(e) {
    //         if (e.target && e.target.nodeName === 'LI') {
    //             if (e.target.classList.contains('checked')) {

    //                 e.target.remove();

    //                 // Retire l'élément du localStorage
    //                 removeIngredientFromShoppingList(e.target.dataset.index);
    //             } else {
    //                 // Ajoutez la classe 'checked' et l'icône de vérification
    //                 e.target.classList.add('checked');
    //             }
    //         }
    //     });

    //     //  AJOUTE UN INGREDIENT AJOUTE MANUELLEMENT

    //     document.getElementById('add-ingredient').addEventListener('click', function() {
    //         let name = document.getElementById('new-ingredient-name').value;

    //         if (name) {
    //             let newIngredient = {
    //                 name
    //             };
    //             addIngredientToShoppingList(newIngredient);
    //             displayShoppingList();
    //         }
    //     });
    // });


    // //   AFFICHER LA LISTE DES INGREDIENTS COCHES SUR LA RECETTE

    // function displayShoppingList() {
    //     let shoppingList = JSON.parse(localStorage.getItem('shoppingList')) || [];
    //     let listContainer = document.getElementById('shopping-list');
    //     listContainer.innerHTML = '';

    //     shoppingList.forEach(function(ingredient, index) {
    //         let listItem = document.createElement('li');

    //         if (ingredient.quantity && ingredient.unit) {
    //             listItem.textContent = `${ingredient.quantity} ${ingredient.unit} de ${ingredient.name}`;
    //         } else {
    //             listItem.textContent = `${ingredient.name}`;
    //         }
    //         // Stocke l'index dans le dataset
    //         listItem.dataset.index = index;
    //         listContainer.appendChild(listItem);
    //     });
    // }



    // //   AJOUTE LES INGREDIENTS DANS LE LOCALSTORAGE
    // function addIngredientToShoppingList(ingredient) {
    //     let shoppingList = JSON.parse(localStorage.getItem('shoppingList')) || [];
    //     shoppingList.push(ingredient);
    //     localStorage.setItem('shoppingList', JSON.stringify(shoppingList));
    // }


    // //   RETITRE LES INGREDIENTS DE LA LISTE

    // function removeIngredientFromShoppingList(index) {
    //     let shoppingList = JSON.parse(localStorage.getItem('shoppingList')) || [];
    //     shoppingList.splice(index, 1); // Supprimez l'élément à l'index spécifié
    //     localStorage.setItem('shoppingList', JSON.stringify(shoppingList));
    //     displayShoppingList(); // Mettez à jour l'affichage
    // }
</script>








<?php
// debug($_POST);
require_once "inc/footer.inc.php";

?>