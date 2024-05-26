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


function deconnexionBdd()
{
    return null;
}

// ***********************************  CONNEXION / INSCRIPTION  ********************************************


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

// *************************************  UTILISATEURS ADMIN  *****************************************************


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



// *************************************  PROFIL UTILISATEUR  ************************************************** 

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


function updateImgUser(int $id, string $image): void
{
    $pdo = connexionBdd();

    $sql = "UPDATE users SET image = :image WHERE id = :id";

    $request = $pdo->prepare($sql);
    $request->execute(array(
        ':id' => $id,
        ':image' => $image
      
    ));
}



// **************************************  RECETTES ADMIN ************************************************

//   RECUPERER TOUTES LES RECETTES

/**
 * fonction qui recupere toutes les recettes
 *
 * @return array
 */
function allRecipes(): array
{

    $pdo = connexionBdd();
    $sql = "SELECT r.id, r.name, r.slug, r.image, r.instructions, r.typePlat, r.season, r.price, r.time,
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

function addRecipe(string $name, string $slug, string $image, string $instructions, string $typePlat, string $season, string $price, string $time, array $categories, array $ingredients): void
{
    $pdo = connexionBdd();

    $sql = "INSERT INTO recipes (name, slug, image, instructions, typePlat, season, price, time) VALUES (:name, :slug, :image, :instructions, :typePlat, :season, :price, :time)";

    $request = $pdo->prepare($sql);
    $request->execute(array(
        ':name' => $name,
        ':slug' => $slug,
        ':image' => $image,
        ':instructions' => $instructions,
        ':typePlat' => $typePlat,
        ':season' => $season,
        ':price' => $price,
        ':time' => $time
    ));


    $recipeId = $pdo->lastInsertId();

    // Ajout des catégories
    foreach ($categories as $categoryId) {
        $sql = "INSERT INTO recipe_category (recipe_id, category_id) VALUES (:recipeId, :categoryId)";
        $request = $pdo->prepare($sql);
        $request->execute(array(
            ':recipeId' => $recipeId,
            ':categoryId' => $categoryId
        ));
    }

    // Ajout des ingrédients
    foreach ($ingredients as $ingredientId => $ingredientInfo) {

        if (isset($ingredientInfo['checked'])) {

            $quantity = floatval($ingredientInfo['quantity']);
            $unity = $ingredientInfo['unity'];

            $sql = "INSERT INTO recipe_ingredients (recipe_id, ingredient_id, quantity, unity) VALUES (:recipeId, :ingredientId, :quantity, :unity)";

            $request = $pdo->prepare($sql);
            $request->execute(array(
                ':recipeId' => $recipeId,
                ':ingredientId' => $ingredientId,
                ':quantity' => $quantity,
                ':unity' => $unity
            ));
        }
    }
}


//   POUR METTRE A JOUR UNE RECETTE

function updateRecipe(int $id, string $name, string $slug, string $image, string $instructions, string $typePlat, string $season, string $price, string $time, array $categories, array $ingredients): void
{
    $pdo = connexionBdd();

    $sql = "UPDATE recipes SET name=:name, slug=:slug, image=:image, instructions=:instructions, typePlat=:typePlat, season=:season, price=:price, time=:time WHERE id=:id";

    $request = $pdo->prepare($sql);
    $request->execute(array(
        ':id' => $id,
        ':name' => $name,
        ':slug' => $slug,
        ':image' => $image,
        ':instructions' => $instructions,
        ':typePlat' => $typePlat,
        ':season' => $season,
        ':price' => $price,
        ':time' => $time
    ));


    $sql = "DELETE FROM recipe_category WHERE recipe_id=:id";

    $request = $pdo->prepare($sql);
    $request->execute(array(':id' => $id));

    $sql = "DELETE FROM recipe_ingredients WHERE recipe_id=:id";

    $request = $pdo->prepare($sql);
    $request->execute(array(':id' => $id));


    foreach ($categories as $categoryId) {

        $sql = "INSERT INTO recipe_category (recipe_id, category_id) VALUES (:recipeId, :categoryId)";

        $request = $pdo->prepare($sql);
        $request->execute(array(
            ':recipeId' => $id,
            ':categoryId' => $categoryId
        ));
    }


    foreach ($ingredients as $ingredientId => $ingredientInfo) {

        if (isset($ingredientInfo['checked'])) {

            $quantity = floatval($ingredientInfo['quantity']);
            $unity = $ingredientInfo['unity'];

            $sql = "INSERT INTO recipe_ingredients (recipe_id, ingredient_id, quantity, unity) VALUES (:recipeId, :ingredientId, :quantity, :unity)";

            $request = $pdo->prepare($sql);
            $request->execute(array(
                ':recipeId' => $id,
                ':ingredientId' => $ingredientId,
                ':quantity' => $quantity,
                ':unity' => $unity
            ));
        }
    }
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



//   AFFICHER UNE RECETTE

function showRecipe(int $recipe_id): mixed
{
    $pdo = connexionBdd();
    $sql = "SELECT r.id, r.name, r.slug, r.image, r.instructions, r.typePlat, r.season, r.price, r.time,
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



//   POUR RECUPERER TOUS LES INGREDIENTS D'UNE RECETTE


function showIngredientsRecipe(int $id): mixed
{
    $pdo = connexionBdd();
    $sql = "SELECT recipe_ingredients.*, ingredients.name AS ingredient, recipe_ingredients.quantity AS quantity, recipe_ingredients.unity AS unity
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


// *************************************  CATEGORIES ADMIN  ****************************************************


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

function addCategory(string $name): void
{

    $pdo = connexionBdd();

    $sql = "INSERT IGNORE INTO categories (name) VALUES (:name)";

    $request = $pdo->prepare($sql);
    $request->execute(array(

        ':name' => $name,
    ));
}


//   MODIFIER UNE CATEGORIE


function updateCategory(int $id, string $name): void
{
    $pdo = connexionBdd();
    $sql = "UPDATE recipes SET name = :name WHERE id = :id";

    $request = $pdo->prepare($sql);
    $request->execute(array(
        ':id' => $id,
        ':name' => $name
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


// ****************************************  INGREDIENTS ADMIN  *************************************************


//   RECUPERER TOUS LES INGREDIENTS

function allIngredients(): array
{

    $pdo = connexionBdd();
    $sql = "SELECT * FROM ingredients ORDER BY id DESC";
    $request = $pdo->query($sql);
    $result = $request->fetchAll();
    return $result;
}


//   AJOUTER UN INGREDIENT

function addIngredient(string $name): string
{
    $pdo = connexionBdd();
    try {
        $sql = "INSERT INTO ingredients (name) VALUES (:name)";
        $request = $pdo->prepare($sql);
        $request->execute(array(':name' => $name));
        return "L'ingrédient a été ajouté avec succès.";
    } catch (PDOException $e) {
        if ($e->getCode() == '23000' && strpos($e->getMessage(), 'Duplicate entry') !== false) {
            return "L'ingrédient existe déjà.";
        } else {
            return "Erreur : " . $e->getMessage();
        }
    }
}



//   MODIFIER UN INGREDIENT

function updateIngredient(int $id, string $name): void
{
    $pdo = connexionBdd();
    $sql = "UPDATE ingredients SET name = :name WHERE id = :id";

    $request = $pdo->prepare($sql);
    $request->execute(array(
        ':id' => $id,
        ':name' => $name
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


// *****************************************  PAGE RECETTES  ************************************

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





// ***************************************  FAVORIS  ***********************************************



//   FONCTION POUR AJOUTER UNE RECETTE AUX FAVORIS

function addRecipeToFavorites(int $userId, int $recipeId): bool
{
    $pdo = connexionBdd();
    $sql = "INSERT INTO users_recipes (user_id, recipe_id, aime) VALUES (:userId, :recipeId, 1)
            ON DUPLICATE KEY UPDATE aime = 1";
    $request = $pdo->prepare($sql);
    $result = $request->execute(array(':userId' => $userId, ':recipeId' => $recipeId));

    return $result;
}


//   FONCTION POUR SUPPRIMER UNE RECETTE FAVORITE


//   FONCTION POUR RECUPERER LES RECETTES FAVORITES D'UN UTILISATEUR

function allFavoriteRecipes(int $userId): array
{
    $pdo = connexionBdd();
    $sql = "SELECT ur.recipe_id, r.name FROM users_recipes ur 
            JOIN recipes r ON ur.recipe_id = r.id 
            WHERE ur.user_id = :userId AND ur.aime = 1";
    $request = $pdo->prepare($sql);
    $request->execute([':userId' => $userId]);

    $favoriteRecipes = $request->fetchAll(PDO::FETCH_ASSOC);

    return $favoriteRecipes;
}




//   FONCTION POUR AJOUTER UNE RECETTE A LA BLACKLIST

function addRecipeToBlacklist(int $userId, int $recipeId): bool
{
    $pdo = connexionBdd();
    $sql = "INSERT INTO users_recipes (user_id, recipe_id, aime) VALUES (:userId, :recipeId, 0)
            ON DUPLICATE KEY UPDATE aime = 0";
    $request = $pdo->prepare($sql);
    $result = $request->execute(array(':userId' => $userId, ':recipeId' => $recipeId));

    return $result;
}



//   FONCTION POUR RECUPERER LES RECETTES BLACKLISTEES D'UN UTILISATEUR

function allBlacklistRecipes(int $userId): array
{
    $pdo = connexionBdd();
    $sql = "SELECT ur.recipe_id, r.name FROM users_recipes ur 
            JOIN recipes r ON ur.recipe_id = r.id 
            WHERE ur.user_id = :userId AND ur.aime = 0";
    $request = $pdo->prepare($sql);
    $request->execute([':userId' => $userId]);

    $blacklistRecipes = $request->fetchAll(PDO::FETCH_ASSOC);

    return $blacklistRecipes;
}





// ******************************************  PAGE MENUS  *************************************************


//   POUR AJOUTER UN MENU A LA TABLE MENUS

function addMenu(int $user_id, int $jours, int $pers)
{
    $pdo = connexionBdd();
    $sql = "INSERT INTO menus (user_id, nb_jours, nb_pers) VALUES (:user_id, :nb_jours, :nb_pers)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':user_id' => $user_id,
        ':nb_jours' => $jours,
        ':nb_pers' => $pers
    ]);
    return $pdo->lastInsertId(); // Retourne l'ID du menu inséré
}



//    RECUPERER LES RECETTES DU FORMULAIRE 


function getRecipesByType($mealType, $season, $price, $time, $categories, $nb_jours): array
{
    $pdo = connexionBdd();

    // Construire la partie IN de la requête avec des paramètres nommés pour les catégories
    $inParams = [];
    $in = '';
    if (!empty($categories)) {
        foreach ($categories as $index => $value) {
            $inParams[] = ':category' . $index;
        }
        $in = implode(', ', $inParams);
    }

    // Préparer la requête SQL
    $sql = "
        SELECT DISTINCT r.id, r.name, r.season, r.price, r.time
        FROM recipes r
        JOIN recipe_category rc ON r.id = rc.recipe_id
        WHERE r.typePlat = :mealType ";

    // Ajouter la clause pour la saison si elle est sélectionnée
    if (!empty($season)) {
        $sql .= "AND r.season = :season ";
    }

    // Ajouter la clause pour le prix si il est sélectionné
    if (!empty($price)) {
        $sql .= "AND r.price = :price ";
    }

    // Ajouter la clause pour le temps si il est sélectionné
    if (!empty($time)) {
        $sql .= "AND r.time = :time ";
    }

    // Ajouter la clause IN pour les catégories si elles sont sélectionnées
    if (!empty($in)) {
        $sql .= "AND rc.category_id IN ($in) ";
    }

    // Ajouter la clause LIMIT
    $sql .= "ORDER BY RAND() LIMIT :nb_jours";

    // Préparation de la requête
    $requete = $pdo->prepare($sql);

    // Liaison des paramètres
    $requete->bindParam(':mealType', $mealType);
    $requete->bindValue(':nb_jours', (int) $nb_jours, PDO::PARAM_INT);

    // Liaison des paramètres pour la saison, le prix et le temps si ils sont sélectionnés
    if (!empty($season)) {
        $requete->bindParam(':season', $season);
    }
    if (!empty($price)) {
        $requete->bindParam(':price', $price);
    }
    if (!empty($time)) {
        $requete->bindParam(':time', $time);
    }

    // Liaison des paramètres pour les catégories si elles sont sélectionnées
    foreach ($inParams as $index => $param) {
        $requete->bindValue($param, $categories[$index]);
    }

    // Exécution de la requête
    $requete->execute();
    return $requete->fetchAll(PDO::FETCH_ASSOC);
}



//   INSERER LES RECETTES DANS LA TABLE MENUS_RECETTES


function insertRecipesToMenu($menu_id, $entrees_ids, $plats_ids, $desserts_ids)
{
    $pdo = connexionBdd();

    // Insérer les entrees
    foreach ($entrees_ids as $entree_id) {
        $sql = "INSERT INTO menu_recettes (menu_id, recipe_id) VALUES (:menu_id, :recipe_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':menu_id', $menu_id, PDO::PARAM_INT);
        $stmt->bindParam(':recipe_id', $entree_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Insérer les plats
    foreach ($plats_ids as $plat_id) {
        $sql = "INSERT INTO menu_recettes (menu_id, recipe_id) VALUES (:menu_id, :recipe_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':menu_id', $menu_id, PDO::PARAM_INT);
        $stmt->bindParam(':recipe_id', $plat_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Insérer les desserts
    foreach ($desserts_ids as $dessert_id) {
        $sql = "INSERT INTO menu_recettes (menu_id, recipe_id) VALUES (:menu_id, :recipe_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':menu_id', $menu_id, PDO::PARAM_INT);
        $stmt->bindParam(':recipe_id', $dessert_id, PDO::PARAM_INT);
        $stmt->execute();
    }
}


// RECUPERE LE NOMBRE DE JOURS ET NOMBRE DE PERSONNES DU MENU CREE

function getMenuInfoById($menu_id)
{
    $pdo = connexionBdd();

    $sql = "SELECT nb_jours, nb_pers FROM menus WHERE id = :menu_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':menu_id', $menu_id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}


//  RECUPERE LES NOMS DES RECETTES DU MENU CREE

function getRecipeNamesForMenu($menu_id)
{
    $pdo = connexionBdd();
    $sql = "
        SELECT r.id, r.name, r.typePlat
        FROM recipes r
        INNER JOIN menu_recettes mr ON r.id = mr.recipe_id
        WHERE mr.menu_id = :menu_id
    ";

    $requete = $pdo->prepare($sql);
    $requete->bindParam(':menu_id', $menu_id, PDO::PARAM_INT);
    $requete->execute();
    return $requete->fetchAll(PDO::FETCH_ASSOC);
}


//   RECUPERE LE DERNIER MENU DE L'UTILISATEUR CONNECTE

function getLastMenuIdByUserId($user_id)
{
    $pdo = connexionBdd();

    // Sélectionnez l'ID du dernier menu pour cet utilisateur
    $sql = "SELECT id FROM menus WHERE user_id = :user_id ORDER BY id DESC LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    // Récupérez le résultat
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Retournez l'ID du menu ou null si aucun menu n'a été trouvé
    return $result ? $result['id'] : null;
}




// ***************************************** LISTE DE COURSES **********************************************

//   AJOUTER UN INGREDIENT A LA LISTE DE COURSE

function addIngredientToList($userId, $name, $quantity, $unity)
{
    $pdo = connexionBdd();

    $sql = "INSERT INTO list (user_id, name, quantity, unity)
            VALUES (:user_id, :name, :quantity, :unity)";

    $request = $pdo->prepare($sql);

    $quantity = is_numeric($quantity) ? $quantity : NULL;

    $request->execute(array(
        ':user_id' => $userId,
        ':name' => $name,
        ':quantity' => $quantity ?? null,
        ':unity' => $unity ?? null
    ));
}

//   SUPPRIMER UN INGREDIENT DE LA LISTE 

function deleteIngredientFromList(int $id): void
{

    $pdo = connexionBdd();

    $sql = "DELETE FROM list WHERE id = :id";

    $request = $pdo->prepare($sql);
    $request->execute([':id' => $id]);
}


//   MONTRER LA DERNIERE LISTE DE COURSES CREEE

function showList(): array
{
    $pdo = connexionBdd();

    $sql = "SELECT * FROM list";

    $request = $pdo->query($sql);
    $result = $request->fetchAll();
    return $result;
}
