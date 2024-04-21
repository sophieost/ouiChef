<?php

// CHEMIN ABSOLU

session_start();

define("RACINE_SITE", "/projet_de_soutenance/ouiChef/"); // 


// DEBUG
/**
 * fonction pour afficher un tableau ordonné via var_dump
 *
 * @param [type] $var
 * @return void
 */

function debug($var)
{

    echo '<pre class="border border-dark bg-light text-primary w-50 p-3">';

    var_dump($var);

    echo '</pre>';
}


// ALERT

function alert(string $contenu, string $class)
{

    return "<div class='alert alert-$class alert-dismissible fade show text-center w-75 m-auto mb-5' role='alert'>
        $contenu

            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>

        </div>";
}


// FONCTION EXPLODE - STRING TO ARRAY
/**
 * fonction qui transforme une chaine de caractères en array
 *
 * @param string $string
 * @return array
 */
function stringToArray(string $string): array
{

    $array = explode('/', trim($string));
    return $array;
}


// DECONNEXION DE LA SESSION

function logOut()
{

    if (isset($_GET['action']) && !empty($_GET['action']) && $_GET['action'] == 'deconnexion') {


        unset($_SESSION['user']);


        header("location:" . RACINE_SITE . "index.php");
    }
}

//  CONNEXION DATABASE

define("DBHOST", "localhost");

// Constante de l'utilisateur de la BDD du serveur en local  => root
define("DBUSER", "root");

// Constante pour le mot de passe de serveur en local => pas de mot de passe
define("DBPASS", "");

// Constante pour le nom de la BDD
define("DBNAME", "ouichef");


function connexionBdd()
{

    $dsn = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8";

    try {

        $pdo = new PDO($dsn, DBUSER, DBPASS);

        // On définit le mode d'erreur de PDO sur Exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {

        die($e->getMessage());
    }

    return $pdo;
}
// connexionBdd();


//  DECONNEXION DATABASE 


function deconnexionBdd($pdo)
{
    $pdo = null;
}


//  INSCRIPTION USERS

function inscriptionUsers(string $pseudo, string $email, string $mdp): void
{

    $pdo = connexionBdd(); // je stock ma connexion  à la BDD dans une variable

    $sql = "INSERT INTO users 
        (pseudo, email, mdp)
        VALUES
        (:pseudo, :email, :mdp)"; // Requête d'insertion que je stock dans une variable
    $request = $pdo->prepare($sql); // Je prépare ma requête et je l'exécute
    $request->execute(
        array(
            ':pseudo' => $pseudo,
            ':email' => $email,
            ':mdp' => $mdp,
        )
    );
}



//   VERIFIER SI UN MAIL EXISTE DANS LA DB

function checkEmailUser(string $email): mixed
{
    $pdo = connexionBdd();
    $sql = "SELECT * FROM users WHERE email = :email";
    $request = $pdo->prepare($sql);
    $request->execute(array(
        ':email' => $email

    ));

    $resultat = $request->fetch();
    return $resultat;
}



//   VERIFIER UN UTILISATEUR ( SI L'EMAIL CORRESPOND AU USER)

function checkUser(string $emailSaisi): mixed
{

    $pdo = connexionBdd();

    $sql = "SELECT * FROM users WHERE email = :email";
    $request = $pdo->prepare($sql);
    $request->bindParam(':email', $emailSaisi);
    $request->execute();
    $resultat = $request->fetch();
    return $resultat;
}

// ***********************************************************************************


//   RECUPERATION DE TOUS LES UTILISATEURS

function allUsers(): array
{

    $pdo = connexionBdd();
    $sql = "SELECT * FROM users";
    $request = $pdo->query($sql);
    $result = $request->fetchAll();
    return $result;
}

//   RECUPERATION D'UN UTILISATEUR

function showUser(int $id): array
{
    $pdo = connexionBdd();
    $sql = "SELECT * FROM users WHERE id = :id";
    $request = $pdo->prepare($sql);
    $request->execute(array(

        ':id' => $id

    ));
    $result = $request->fetch();
    return $result;
}


//   SUPPRIMER UN UTILISATEUR

function deleteUser(int $id): void
{
    $pdo = connexionBdd();
    $sql = "DELETE FROM users WHERE id = :id";
    $request = $pdo->prepare($sql);
    $request->execute(array(

        ':id' => $id

    ));
}

//   MODIFIER LE ROLE D'UN UTILISATEUR

function updateRole(string $role, int $id): void
{
    $pdo = connexionBdd();
    $sql = "UPDATE users SET role = :role WHERE id = :id";
    $request = $pdo->prepare($sql);
    $request->execute(array(
        ':role' => $role,
        ':id' => $id

    ));
}



//  UTILISATEUR PEUT MODIFIER SES INFOS


function updateUser(int $id, string $pseudo, string $email, string $mdp): void
{
    $pdo = connexionBdd();

    $sql = "UPDATE users SET pseudo = :pseudo, email = :email, mdp = :mdp WHERE id = :id";

    $request = $pdo->prepare($sql);
    $request->execute(array(
        ':id' => $id,
        ':pseudo' => $pseudo,
        ':email' => $email,
        ':mdp' => $mdp,
    ));
}



// ***********************************************************************************

//   RECUPERER TOUTES LES RECETTES

/**
 * fonction qui recupere toutes les recettes
 *
 * @return array
 */
function allRecipes(): array
{

    $pdo = connexionBdd();
    $sql = "SELECT r.id, r.name, r.slug, r.image, r.instructions, r.repas, r.typePlat, r.season, r.price, r.time,
    GROUP_CONCAT(DISTINCT cat.name ORDER BY cat.name ASC SEPARATOR ', ') AS categories,
    GROUP_CONCAT(DISTINCT CONCAT(ing.name, ' : ', ri.quantity, ' ', ri.unity) ORDER BY ing.name ASC SEPARATOR ', ') AS ingredients
    FROM recipes r
    LEFT JOIN recipe_category rc ON r.id = rc.recipe_id
    LEFT JOIN categories cat ON rc.category_id = cat.id
    LEFT JOIN recipe_ingredients ri ON r.id = ri.recipe_id
    LEFT JOIN ingredients ing ON ri.ingredient_id = ing.id
    GROUP BY r.id";
    $request = $pdo->query($sql);
    $result = $request->fetchAll();
    return $result;
}


//   POUR AJOUTER UNE RECETTE

// function addRecipe(string $name, string $slug, string $image, string $instructions, string $repas, string $typePlat, string $season, string $price, string $time): void
// {
//     $pdo = connexionBdd();

//     $sql = "INSERT INTO recipes (name, slug, image, instructions, repas, typePlat, season, price, time) VALUES (:name, :slug, :image, :instructions, :repas, :typePlat, :season, :price, :time)";
//     $request = $pdo->prepare($sql);
//     $result = $request->execute(array(
//         ':name' => $name,
//         ':slug' => $slug,
//         ':image' => $image,
//         ':instructions' => $instructions,
//         ':repas' => $repas,
//         ':typePlat' => $typePlat,
//         ':season' => $season,
//         ':price' => $price,
//         ':time' => $time
//     ));
// }



function addRecipe($name, $slug, $image, $instructions, $repas, $typePlat, $season, $price, $time, $categories, $ingredients)
{
    $pdo = connexionBdd();

    $sql = "INSERT INTO recipes (name, slug, image, instructions, repas, typePlat, season, price, time) VALUES (:name, :slug, :image, :instructions, :repas, :typePlat, :season, :price, :time)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':slug', $slug);
    $stmt->bindParam(':image', $image);
    $stmt->bindParam(':instructions', $instructions);
    $stmt->bindParam(':repas', $repas);
    $stmt->bindParam(':typePlat', $typePlat);
    $stmt->bindParam(':season', $season);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':time', $time);
    $stmt->execute();

    $recipeId = $pdo->lastInsertId();

    // Ajout des catégories
    $categoryIds = explode(',', $categories);
    foreach ($categoryIds as $categoryId) {
        $sql = "INSERT INTO recipe_category (recipe_id, category_id) VALUES (:recipeId, :categoryId)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':recipeId', $recipeId);
        $stmt->bindParam(':categoryId', $categoryId);
        $stmt->execute();
    }

    // Ajout des ingrédients
    $ingredientData = explode(',', $ingredients);
    foreach ($ingredientData as $ingredientInfo) {
        list($ingredientId, $quantity, $unity) = explode(':', $ingredientInfo);
        $sql = "INSERT INTO recipe_ingredients (recipe_id, ingredient_id, quantity, unity) VALUES (:recipeId, :ingredientId, :quantity, :unity)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':recipeId', $recipeId);
        $stmt->bindParam(':ingredientId', $ingredientId);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':unity', $unity);
        $stmt->execute();
    }
}



function updateRecipe($id, $name, $slug, $image, $instructions, $repas, $typePlat, $season, $price, $time, $categories, $ingredients)
{
    $pdo = connexionBdd();

    $sql = "UPDATE recipes SET name=:name, slug=:slug, image=:image, instructions=:instructions, repas=:repas, typePlat=:typePlat, season=:season, price=:price, time=:time WHERE id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':slug', $slug);
    $stmt->bindParam(':image', $image);
    $stmt->bindParam(':instructions', $instructions);
    $stmt->bindParam(':repas', $repas);
    $stmt->bindParam(':typePlat', $typePlat);
    $stmt->bindParam(':season', $season);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':time', $time);
    $stmt->execute();

    // Suppression des anciennes catégories et ingrédients
    $sql = "DELETE FROM recipe_category WHERE recipe_id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $sql = "DELETE FROM recipe_ingredients WHERE recipe_id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Ajout des nouvelles catégories
    $categoryIds = explode(',', $categories);
    foreach ($categoryIds as $categoryId) {
        $sql = "INSERT INTO recipe_category (recipe_id, category_id) VALUES (:recipeId, :categoryId)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':recipeId', $id);
        $stmt->bindParam(':categoryId', $categoryId);
        $stmt->execute();
    }

    // Ajout des nouvelles ingrédients
    $ingredientData = explode(',', $ingredients);
    foreach ($ingredientData as $ingredientInfo) {
        list($ingredientId, $quantity, $unity) = explode(':', $ingredientInfo);
        $sql = "INSERT INTO recipe_ingredients (recipe_id, ingredient_id, quantity, unity) VALUES (:recipeId, :ingredientId, :quantity, :unity)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':recipeId', $id);
        $stmt->bindParam(':ingredientId', $ingredientId);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':unity', $unity);
        $stmt->execute();
    }
}


//   AJOUTER LA RELATION RECETTE/CATEGORIES DANS LA TABLE RECIPE_CATEGORIES

// function addRecipeCategories(int $recipe_id, int $category_id): void
// {
//     $pdo = connexionBdd();
//     $sql = "INSERT INTO recipe_category (recipe_id, category_id) VALUES (:id, :category_id)";
//     $request = $pdo->prepare($sql);
//     $request->execute(array(
//         ':id' => $recipe_id,
//         ':category_id' => $category_id
//     ));
// }



//   AJOUTER LA RELATION RECETTE/INGREDIENTS DANS LA TABLE RECIPE_INGREDIENTS

// function addRecipeIngredients(int $recipe_id, int $ingredient_id, float $quantity, string $unity): void
// {
//     $pdo = connexionBdd();
//     $sql = "INSERT INTO recipe_ingredients (recipe_id, ingredient_id, quantity, unity) VALUES (:id, :ingredient_id, :quantity, :unity)";
//     $request = $pdo->prepare($sql);
//     $request->execute(array(
//         ':id' => $recipe_id,
//         ':ingredient_id' => $ingredient_id,
//         ':quantity' => $quantity,
//         ':unity' => $unity
//     ));
// }




//   POUR SUPPRIMER UNE RECETTE
function deleteRecipe(int $id): bool
{
    $pdo = connexionBdd();

    // Suprime une association dans la table recipe_category
    $sql = "DELETE FROM recipe_category WHERE recipe_id = :id";
    $request = $pdo->prepare($sql);
    $request->execute(array(':id' => $id));

    // Suprime une association dans la table recipe_ingredients
    $sql = "DELETE FROM recipe_ingredients WHERE recipe_id = :id";
    $request = $pdo->prepare($sql);
    $request->execute(array(':id' => $id));

    // Supprime la recette
    $sql = "DELETE FROM recipes WHERE id = :id";
    $request = $pdo->prepare($sql);
    $result = $request->execute(array(':id' => $id));

    return $result;
}



//   POUR MODIFIER UNE RECETTE

// function updateRecipe(int $id, string $name, string $slug, string $image, string $instructions, string $repas, string $typePlat, string $season, string $price, string $time): void
// {
//     $pdo = connexionBdd();
//     $sql = "UPDATE recipes SET name = :name, slug = :slug, image = :image, instructions = :instructions, repas = :repas, typePlat = :typePlat, season = :season, price = :price, time = :time WHERE id = :id";
//     $request = $pdo->prepare($sql);
//     $request->execute([
//         ':id' => $id,
//         ':name' => $name,
//         ':slug' => $slug,
//         ':image' => $image,
//         ':instructions' => $instructions,
//         ':repas' => $repas,
//         ':typePlat' => $typePlat,
//         ':season' => $season,
//         ':price' => $price,
//         ':time' => $time
//     ]);
// }


//   AFFICHER UNE RECETTE

function showRecipe(int $recipe_id): mixed
{
    $pdo = connexionBdd();
    $sql = "SELECT r.id, r.name, r.slug, r.image, r.instructions, r.repas, r.typePlat, r.season, r.price, r.time,
    GROUP_CONCAT(DISTINCT cat.name ORDER BY cat.name ASC SEPARATOR ', ') AS categories,
    GROUP_CONCAT(DISTINCT CONCAT(ing.name, ' : ', ri.quantity, ' ', ri.unity) ORDER BY ing.name ASC SEPARATOR ', ') AS ingredients
    FROM recipes r
    LEFT JOIN recipe_category rc ON r.id = rc.recipe_id
    LEFT JOIN categories cat ON rc.category_id = cat.id
    LEFT JOIN recipe_ingredients ri ON r.id = ri.recipe_id
    LEFT JOIN ingredients ing ON ri.ingredient_id = ing.id
    WHERE r.id = :id
    GROUP BY r.id";
    $request = $pdo->prepare($sql);
    $request->execute(array(
        ':id' => $recipe_id
    ));

    $result = $request->fetch();
    return $result;
}


//   MODIFIER LA RELATION RECETTE/CATEGORIES DANS LA TABLE RECIPE_CATEGORIES

// function updateRecipeCategories(int $recipe_id, array $category_id): void
// {
//     $pdo = connexionBdd();
//     $sql = "INSERT INTO recipe_category (recipe_id, category_id) VALUES (:recipe_id, :category_id) ON DUPLICATE KEY UPDATE category_id = :category_id";
//     $request = $pdo->prepare($sql);
//     $request->execute([
//         ':recipe_id' => $recipe_id,
//         ':category_id' => $category_id
//     ]);
// }


//   MODIFIER LA RELATION RECETTE/INGREDIENTS DANS LA TABLE RECIPE_INGREDIENTS

// function updateRecipeIngredients(int $recipe_id, int $ingredient_id, float $quantity, string $unity): void
// {
//     $pdo = connexionBdd();

//     if (isset($ingredient['checked'])) {
//         $sql = "INSERT INTO recipe_ingredients (recipe_id, ingredient_id, quantity, unity) VALUES (:recipe_id, :ingredient_id, :quantity, :unity) ON DUPLICATE KEY UPDATE quantity = :quantity, unity = :unity";
//         $request = $pdo->prepare($sql);
//         $request->execute([
//             ':recipe_id' => $recipe_id,
//             ':ingredient_id' => $ingredient_id,
//             ':quantity' => $quantity,
//             ':unity' => $unity
//         ]);
//     }
// }




//   POUR RECUPERER TOUS LES INGREDIENTS D'UNE RECETTE


// function showIngredientsRecipe(int $id): mixed
// {
//     $pdo = connexionBdd();
//     $sql = "SELECT recipe_ingredients.*, ingredients.name AS ingredient
//     FROM recipe_ingredients
//     LEFT JOIN ingredients
//     ON recipe_ingredients.ingredient_id = ingredients.id
//     WHERE recipe_id = :id ";
//     $request = $pdo->prepare($sql);
//     $request->execute(array(
//         ':id' => $id
//     ));

//     $result = $request->fetchAll();
//     return $result;
// }


//   POUR RECUPERER LES CATEGORIES D'UNE RECETTE

// function showCategoriesRecipe(int $id): mixed
// {
//     $pdo = connexionBdd();
//     $sql = "SELECT recipe_category.*, categories.name AS name
//     FROM recipe_category
//     LEFT JOIN categories
//     ON recipe_category.category_id = categories.id
//     WHERE recipe_id = :id ";
//     $request = $pdo->prepare($sql);
//     $request->execute(array(
//         ':id' => $id
//     ));

//     $result = $request->fetchAll();
//     return $result;
// }


// **********************************************************************************


//   RECUPERER TOUTES LES CATEGORIES 

function allCategories(): array
{

    $pdo = connexionBdd();
    $sql = "SELECT * FROM categories";
    $request = $pdo->query($sql);
    $result = $request->fetchAll();
    return $result;
}


//   AJOUTER UNE CATEGORIE

function addCategory(string $categoryName): void
{

    $pdo = connexionBdd();

    $sql = "INSERT IGNORE INTO categories (name) VALUES (:name)";

    $request = $pdo->prepare($sql);
    $request->execute(array(

        ':name' => $categoryName,
    ));
}


//   MODIFIER UNE CATEGORIE


function updateCategory(int $id, string $name, string $image): void
{
    $pdo = connexionBdd();
    $sql = "UPDATE recipes SET name = :name, image = :image WHERE id = :id";

    $request = $pdo->prepare($sql);
    $request->execute(array(
        ':id' => $id,
        ':name' => $name,
        ':image' => $image
    ));
}

//   SUPPRIMER UNE CATEGORIE

function deleteCategory(int $id): void
{
    $pdo = connexionBdd();

    $sql = "DELETE FROM categories WHERE id = :id";
    $request = $pdo->prepare($sql);
    $request->execute(array(':id' => $id));
}

//   AFFICHER UNE CATEGORIE

function showCategory(int $id): mixed
{
    $pdo = connexionBdd();
    $sql = "SELECT * FROM categories WHERE id = :id ";
    $request = $pdo->prepare($sql);
    $request->execute(array(
        ':id' => $id
    ));

    $result = $request->fetch();
    return $result;
}


// **********************************************************************************


//   RECUPERER TOUS LES INGREDIENTS

function allIngredients(): array
{

    $pdo = connexionBdd();
    $sql = "SELECT * FROM ingredients";
    $request = $pdo->query($sql);
    $result = $request->fetchAll();
    return $result;
}


//   AJOUTER UN INGREDIENT

function addIngredient(string $name, string $image): void
{

    $pdo = connexionBdd();

    $sql = "INSERT INTO ingredients (name, image) VALUES (:name, :image)";

    $request = $pdo->prepare($sql);
    $request->execute(array(

        ':name' => $name,
        ':image' => $image
    ));
}


// function addIngredient(string $name, string $image): void
// {
//     $pdo = connexionBdd();

//     // Vérifiez d'abord si un ingrédient avec le même nom existe déjà
//     $checkSql = "SELECT COUNT(*) FROM ingredients WHERE name = :name";
//     $checkStmt = $pdo->prepare($checkSql);
//     $checkStmt->execute([':name' => $name]);
//     $exists = $checkStmt->fetchColumn();

//     if (!$exists) {
//         // Si l'ingrédient n'existe pas, insérez-le
//         $sql = "INSERT INTO ingredients (name, image) VALUES (:name, :image)";
//         $request = $pdo->prepare($sql);
//         $request->execute([
//             ':name' => $name,
//             ':image' => $image
//         ]);
//     } else {

//         $updateSql = "UPDATE ingredients SET image = :image WHERE name = :name";
//         $updateStmt = $pdo->prepare($updateSql);
//         $updateStmt->execute([
//             ':name' => $name,
//             ':image' => $image
//         ]);
//     }
// }


//   MODIFIER UN INGREDIENT

function updateIngredient(int $id, string $name, string $image): void
{
    $pdo = connexionBdd();
    $sql = "UPDATE ingredients SET name = :name, image = :image WHERE id = :id";

    $request = $pdo->prepare($sql);
    $request->execute(array(
        ':id' => $id,
        ':name' => $name,
        ':image' => $image
    ));
}

//   SUPPRIMER UN INGREDIENT

function deleteIngredient(int $id): void
{
    $pdo = connexionBdd();

    $sql = "DELETE FROM ingredients WHERE id = :id";
    $request = $pdo->prepare($sql);
    $request->execute(array(':id' => $id));
}

//   AFFICHER UN INGREDIENT

function showIngredient(int $id): mixed
{
    $pdo = connexionBdd();
    $sql = "SELECT * FROM ingredients WHERE id = :id ";
    $request = $pdo->prepare($sql);
    $request->execute(array(
        ':id' => $id
    ));

    $result = $request->fetch();
    return $result;
}


// *********************************************************************

//   FONCTION POUR RECUPERER LES RECETTES PAR PRIX

function recipesByPrice(string $price): array
{
    $pdo = connexionBdd();
    $sql = "SELECT * FROM recipes WHERE price = :price";
    $request = $pdo->prepare($sql);
    $request->execute(array(':price' => $price));

    $result = $request->fetchAll();
    return $result;
}

//   FONCTION POUR RECUPERER LES RECETTES PAR TEMPS DE PREPARATION

function recipesByTime(string $time): array
{
    $pdo = connexionBdd();
    $sql = "SELECT * FROM recipes WHERE time = :time";
    $request = $pdo->prepare($sql);
    $request->execute(array(':time' => $time));

    $result = $request->fetchAll();
    return $result;
}

//   FONCTION POUR RECUPERER LES RECETTES PAR CATEGORIE

function recipesByCategory(string $categoryName): array
{
    $pdo = connexionBdd();
    $sql = "SELECT recipes.* FROM recipes 
            JOIN recipe_category ON recipes.id = recipe_category.recipe_id
            JOIN categories ON recipe_category.category_id = categories.id
            WHERE categories.name = :categoryName";
    $request = $pdo->prepare($sql);
    $request->execute(array(':categoryName' => $categoryName));

    $result = $request->fetchAll();
    return $result;
}

//   FONCTION POUR RECUPERER LES RECETTES PAR SAISON


function recipesBySeason(string $season): array
{
    $pdo = connexionBdd();
    $sql = "SELECT * FROM recipes WHERE season = :season";
    $request = $pdo->prepare($sql);
    $request->execute(array(':season' => $season));

    $result = $request->fetchAll();
    return $result;
}

//   FONCTION POUR RECUPERER LES RECETTES PAR TYPE DE PLAT


function recipesByPlat(string $typePlat): array
{
    $pdo = connexionBdd();
    $sql = "SELECT * FROM recipes WHERE typePlat = :typePlat";
    $request = $pdo->prepare($sql);
    $request->execute(array(':typePlat' => $typePlat));

    $result = $request->fetchAll();
    return $result;
}

//   FONCTION POUR RECUPERER LES RECETTES PAR REPAS


function recipesByRepas(string $repas): array
{
    $pdo = connexionBdd();
    $sql = "SELECT * FROM recipes WHERE repas = :repas";
    $request = $pdo->prepare($sql);
    $request->execute(array(':repas' => $repas));

    $result = $request->fetchAll();
    return $result;
}


//   FONCTION POUR AJOUTER UNE RECETTE AUX FAVORIS

function addRecipeToFavorites(int $userId, int $recipeId): bool
{
    $pdo = connexionBdd();
    $sql = "INSERT INTO users_recipe (user_id, recipe_id, aime) VALUES (:userId, :recipeId, 1)
            ON DUPLICATE KEY UPDATE aime = 1";
    $request = $pdo->prepare($sql);
    $success = $request->execute(array(':userId' => $userId, ':recipeId' => $recipeId));

    return $success;
}
