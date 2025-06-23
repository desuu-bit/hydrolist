<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Situations du personnel</title>
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
    .container {
        background: #fff;
        padding: 40px 40px 30px 40px;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(24,24,24,0.08);
        margin-top: 60px;
        min-width: 350px;
        text-align: center;
    }
    h1 {
        color: var(--main-green);
        margin-bottom: 10px;
        font-size: 2.2em;
        font-weight: bold;
    }
    h2 {
        color: var(--dark);
        margin-bottom: 30px;
        font-size: 1.3em;
        font-weight: 500;
    }
    ul {
        list-style: none;
        padding: 0;
        margin: 0 0 30px 0;
    }
    ul li {
        margin-bottom: 18px;
    }
    .btn {
        display: inline-block;
        background: var(--main-green);
        color: #fff;
        padding: 12px 28px;
        border-radius: 6px;
        border: none;
        font-size: 1.1em;
        font-weight: 500;
        box-shadow: 0 2px 6px rgba(24,24,24,0.06);
        transition: background 0.2s, transform 0.2s;
        cursor: pointer;
        text-decoration: none;
    }
    .btn:hover {
        background: #219150;
        transform: translateY(-2px) scale(1.03);
        text-decoration: none;
        color: #fff;
    }
    .logout {
        margin-top: 30px;
        display: inline-block;
        color: var(--main-green);
        font-weight: 500;
        text-decoration: none;
        transition: color 0.2s;
    }
    .logout:hover {
        color: #219150;
        text-decoration: underline;
    }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenue, <?= htmlspecialchars($_SESSION['nom']) ?> <?= htmlspecialchars($_SESSION['prenom']) ?></h1>
        <h2>Choisissez une situation :</h2>
        <ul>
            <li>
                <a class="btn" href="employes_par_grade.php">Liste des employés par grade</a>
            </li>
            <li>
                <a class="btn" href="employes_preretraite.php">Liste des employés proches de la retraite</a>
            </li>
            <li>
                <a class="btn" href="employes_retraites.php">Liste des employés retraités (âge &gt; 65 ans)</a>
            </li>
        </ul>
        <p><a class="logout" href="logout.php">Se déconnecter</a></p>
    </div>
</body>
</html>