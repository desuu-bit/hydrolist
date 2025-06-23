<?php
session_start();
require_once 'db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE login = ?");
    $stmt->execute([$login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['mot_de_passe'])) {
        $_SESSION['user_id'] = $user['id_utilisateur'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['prenom'] = $user['prénom'];
        $_SESSION['role'] = $user['rôle'];
        header('Location: page.php');
        exit();
    } else {
        $error = "Identifiant ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Plateforme RH</title>
    <style>
    :root {
        --main-green:rgb(54, 133, 89);
        --dark: #181818;
        --light: #f8f8f8;
        --border: #e0e0e0;
    }
    body {
        background: linear-gradient(135deg, #27ae60cc 0%, #181818cc 100%), 
                    url('hydraulic.png') center/cover no-repeat;
        color: var(--dark);
        font-family: 'Segoe UI', Arial, sans-serif;
        margin: 0;
        padding: 0;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .header-title {
        width: 100vw;
        background: var(--dark);
        color: #fff;
        text-align: center;
        padding: 30px 0 18px 0;
        font-size: 2.1em;
        font-weight: bold;
        letter-spacing: 1px;
        border-bottom: 4px solid var(--main-green);
        margin-bottom: 40px;
    }
    .container {
        background: #fff;
        padding: 40px 40px 30px 40px;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(24,24,24,0.08);
        min-width: 350px;
        text-align: center;
        margin-top: 40px;
    }
    h2 {
        color: var(--main-green);
        margin-bottom: 20px;
        font-size: 1.5em;
        font-weight: 500;
    }
    form {
        margin-top: 10px;
    }
    label {
        display: block;
        margin-bottom: 15px;
        color: var(--dark);
        font-size: 1.1em;
        text-align: left;
    }
    input[type="text"], input[type="password"] {
        width: 100%;
        padding: 10px 12px;
        border-radius: 5px;
        border: 1px solid var(--border);
        font-size: 1em;
        margin-top: 5px;
        margin-bottom: 18px;
        background: #f9f9f9;
    }
    button[type="submit"] {
        background: var(--main-green);
        color: #fff;
        border: none;
        padding: 12px 28px;
        border-radius: 6px;
        font-size: 1.1em;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.2s;
        margin-top: 10px;
    }
    button[type="submit"]:hover {
        background: #219150;
    }
    .error {
        color: #c0392b;
        margin-bottom: 18px;
        font-weight: 500;
    }
    </style>
</head>
<body>
    <div class="header-title">
        Direction générale d'hydraulique
    </div>
    <div class="container">
        <h2>Connexion</h2>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post">
            <label>Login :
                <input type="text" name="login" required>
            </label>
            <label>Mot de passe :
                <input type="password" name="password" required>
            </label>
            <button type="submit">Se connecter</button>
        </form>
    </div>
</body>
</html>