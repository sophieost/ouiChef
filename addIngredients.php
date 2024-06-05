<?php
require_once "inc/functions.inc.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $userId = $_SESSION['user']['id'];
    $ingredients = $_POST['ingredients'];
    $response = [];

    foreach ($ingredients as $ingredient) {
        if (isset($ingredient['checked'])) {
            $name = htmlspecialchars($ingredient['name']);
            $quantity = htmlspecialchars($ingredient['quantity'] ?? '');
            $unity = htmlspecialchars($ingredient['unit'] ?? '');

            // Ajout de validation pour les champs quantity et unity
            if ($quantity === '') {
                $quantity = null; // ou toute valeur par défaut appropriée
            }

            if ($unity === '') {
                $unity = null; // ou toute valeur par défaut appropriée
            }

            addIngredientToList($userId, $name, $quantity, $unity);
            $response[] = "Ingredient $name added successfully.";
        }
    }

    echo json_encode(['success' => true, 'messages' => $response]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
