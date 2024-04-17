<?php

// CHEMIN ABSOLU

session_start();

define("RACINE_SITE", "/projet_de_soutenance/ouiChef/"); // 


// DEBUG

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

function allRecipes(): array
{

    $pdo = connexionBdd();
    $sql = "SELECT r.id, r.name, r.slug, r.image, r.instructions, r.repas, r.plat, r.season, r.price, r.time,
    GROUP_CONCAT(DISTINCT cat.name ORDER BY cat.name ASC SEPARATOR ', ') AS categories,
    GROUP_CONCAT(DISTINCT CONCAT(ing.name, ' : ', ri.quantity, ' ', ri.unité) ORDER BY ing.name ASC SEPARATOR ', ') AS ingredients
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

function addRecipe(string $name, string $slug, string $image, string $instructions, string $repas, string $plat, string $season, float $price, int $time, array $categories, array $ingredients): bool
{
    $pdo = connexionBdd();

    // Ajoute une nouvelle recette
    $sql = "INSERT INTO recipes (name, slug, image, instructions, repas, plat, season, price, time) VALUES (:name, :slug, :image, :instructions, :repas, :plat, :season, :price, :time)";
    $request = $pdo->prepare($sql);
    $result = $request->execute(array(
        ':name' => $name,
        ':slug' => $slug,
        ':image' => $image,
        ':instructions' => $instructions,
        ':repas' => $repas,
        ':plat' => $plat,
        ':season' => $season,
        ':price' => $price,
        ':time' => $time
    ));

    if ($result) {
        $id = $pdo->lastInsertId();

        // Ajoute une association dans la table recipe_category
        foreach ($categories as $category) {
            $sql = "INSERT INTO recipe_category (recipe_id, category_id) VALUES (:id, :category_id)";
            $request = $pdo->prepare($sql);
            $request->execute(array(
                ':id' => $id, 
                ':category_id' => $category
            ));
        }

        // Ajoute une association dans la table recipe_ingredients
        foreach ($ingredients as $ingredient) {
            $sql = "INSERT INTO recipe_ingredients (recipe_id, ingredient_id, quantity, unité) VALUES (:id, :ingredient_id, :quantity, :unité)";
            $request = $pdo->prepare($sql);
            $request->execute(array(':id' => $id, ':ingredient_id' => $ingredient['id'], ':quantity' => $ingredient['quantity'], ':unité' => $ingredient['unité']));
        }
    }

    return $result;
}

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


function updateRecipe(int $id, string $name, string $slug, string $image, string $instructions, string $repas, string $plat, string $season, float $price, int $time, array $categories, array $ingredients): bool
{
    $pdo = connexionBdd();
    $sql = "UPDATE recipes SET name = :name, slug = :slug, image = :image, instructions = :instructions, repas = :repas, plat = :plat, season = :season, price = :price, time = :time WHERE id = :id";
    $request = $pdo->prepare($sql);
    $result = $request->execute(array(
        ':id' => $id,
        ':name' => $name,
        ':slug' => $slug,
        ':image' => $image,
        ':instructions' => $instructions,
        ':repas' => $repas,
        ':plat' => $plat,
        ':season' => $season,
        ':price' => $price,
        ':time' => $time
    ));

    if ($result) {
        // Update categories
        $sql = "DELETE FROM recipe_category WHERE recipe_id = :id";
        $request = $pdo->prepare($sql);
        $request->execute(array(':id' => $id));

        foreach ($categories as $category) {
            $sql = "INSERT INTO recipe_category (recipe_id, category_id) VALUES (:id, :category_id)";
            $request = $pdo->prepare($sql);
            $request->execute(array(':id' => $id, ':category_id' => $category));
        }

        // Update ingredients
        $sql = "DELETE FROM recipe_ingredients WHERE recipe_id = :id";
        $request = $pdo->prepare($sql);
        $request->execute(array(':id' => $id));

        foreach ($ingredients as $ingredient) {
            $sql = "INSERT INTO recipe_ingredients (recipe_id, ingredient_id, quantity, unité) VALUES (:id, :ingredient_id, :quantity, :unité)";
            $request = $pdo->prepare($sql);
            $request->execute(array(':id' => $id, ':ingredient_id' => $ingredient['id'], ':quantity' => $ingredient['quantity'], ':unité' => $ingredient['unité']));
        }
    }

    return $result;
}

//   AFFICHER UNE RECETTE

function showRecipe(int $id): mixed
{
    $pdo = connexionBdd();
    $sql = "SELECT r.id, r.name, r.slug, r.image, r.instructions, r.repas, r.plat, r.season, r.price, r.time,
    GROUP_CONCAT(DISTINCT cat.name ORDER BY cat.name ASC SEPARATOR ', ') AS categories,
    GROUP_CONCAT(DISTINCT CONCAT(ing.name, ' : ', ri.quantity, ' ', ri.unité) ORDER BY ing.name ASC SEPARATOR ', ') AS ingredients
    FROM recipes r
    LEFT JOIN recipe_category rc ON r.id = rc.recipe_id
    LEFT JOIN categories cat ON rc.category_id = cat.id
    LEFT JOIN recipe_ingredients ri ON r.id = ri.recipe_id
    LEFT JOIN ingredients ing ON ri.ingredient_id = ing.id
    WHERE r.id = :id
    GROUP BY r.id";
    $request = $pdo->prepare($sql);
    $request->execute(array(
        ':id' => $id
    ));

    $result = $request->fetch();
    return $result;
}



//   POUR RECUPERER TOUS LES INGREDIENTS D'UNE RECETTE


function showIngredientsRecipe(int $id): mixed
{
    $pdo = connexionBdd();
    $sql = "SELECT recipe_ingredients.*, ingredients.name AS ingredient
    FROM recipe_ingredients
    LEFT JOIN ingredients
    ON recipe_ingredients.ingredient_id = ingredients.id
    WHERE recipe_id = :id ";
    $request = $pdo->prepare($sql);
    $request->execute(array(
        ':id' => $id
    ));

    $result = $request->fetchAll();
    return $result;
}


//   POUR RECUPERER LES CATEGORIES D'UNE RECETTE

function showCategoriesRecipe(int $id): mixed
{
    $pdo = connexionBdd();
    $sql = "SELECT recipe_category.*, categories.name AS name
    FROM recipe_category
    LEFT JOIN categories
    ON recipe_category.category_id = categories.id
    WHERE recipe_id = :id ";
    $request = $pdo->prepare($sql);
    $request->execute(array(
        ':id' => $id
    ));

    $result = $request->fetchAll();
    return $result;
}


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

function addIngredient(string $ingredientName): void
{

    $pdo = connexionBdd();

    $sql = "INSERT IGNORE INTO ingredients (name) VALUES (:name)";

    $request = $pdo->prepare($sql);
    $request->execute(array(

        ':name' => $ingredientName,
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


function recipesByPlat(string $plat): array
{
    $pdo = connexionBdd();
    $sql = "SELECT * FROM recipes WHERE plat = :plat";
    $request = $pdo->prepare($sql);
    $request->execute(array(':plat' => $plat));

    $result = $request->fetchAll();
    return $result;
}