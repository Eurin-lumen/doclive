<?php
// Définir les variables pour stocker les erreurs et les données du formulaire
$usernameError = $nomError = $prenomError = $emailError = $passwordError = $confirmPasswordError = "";
$username = $nom = $prenom = $email = "";

// Vérification si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $username = $_POST["username"];
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm-password"];

    // Validation des champs
    if (empty($username)) {
        $usernameError = "Veuillez entrer votre nom d'utilisateur.";
    }

    if (strlen($nom) <= 5) {
        $nomError = "Le nom doit contenir plus de 5 caractères.";
    }

    if (strlen($prenom) <= 5) {
        $prenomError = "Le prénom doit contenir plus de 5 caractères.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "L'adresse email n'est pas valide.";
    }

    if (empty($password)) {
        $passwordError = "Veuillez entrer votre mot de passe.";
    }

    if ($password !== $confirmPassword) {
        $confirmPasswordError = "Les mots de passe ne correspondent pas.";
    }

    // Si aucune erreur n'est survenue, on peut procéder à l'inscription
    if (empty($usernameError) && empty($nomError) && empty($prenomError) && empty($emailError) && empty($passwordError) && empty($confirmPasswordError)) {
        // Connexion à la base de données
        try {
            $bdd = new PDO('mysql:host=localhost;dbname=doclive;charset=utf8', 'root', '');
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }

        // Hachage du mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Préparation de la requête d'insertion
        $requete = $bdd->prepare("INSERT INTO inscription (nom, prenom, email, motdepasse) VALUES (?, ?, ?, ?)");

        // Exécution de la requête avec les valeurs des champs du formulaire
        $requete->execute([$nom, $prenom, $email, $hashedPassword]);

        // Redirection vers la page de connexion
        header("Location: connexion.php");
        exit(); // Assure que le script s'arrête après la redirection
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire d'Inscription</title>
    <link rel="stylesheet" href="inscript.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include("include/nave.php"); ?>
    
    <div class="container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h2>Formulaire d'Inscription</h2>
            <div class="input-group">
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                <span class="error-message"><?php echo $usernameError; ?></span>
            </div>
            <div class="input-group">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($nom); ?>" required>
                <span class="error-message"><?php echo $nomError; ?></span>
            </div>
            <div class="input-group">
                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($prenom); ?>" required>
                <span class="error-message"><?php echo $prenomError; ?></span>
            </div>
            <div class="input-group">
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                <span class="error-message"><?php echo $emailError; ?></span>
            </div>
            <div class="input-group">
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
                <span class="error-message"><?php echo $passwordError; ?></span>
            </div>
            <div class="input-group">
                <label for="confirm-password">Confirmer le mot de passe :</label>
                <input type="password" id="confirm-password" name="confirm-password" required>
                <span class="error-message"><?php echo $confirmPasswordError; ?></span>
            </div>
            <button type="submit">S'inscrire</button>
        </form>
    </div>

    <?php include("include/footer.php"); ?>
</body>
</html>
