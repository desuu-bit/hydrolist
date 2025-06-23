<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
require_once 'db.php';

$stmt = $pdo->query("
    SELECT e.id_employe, e.nom, e.prenom, e.date_naissance, e.date_recrutement, g.nom_grade,
           TIMESTAMPDIFF(YEAR, e.date_naissance, CURDATE()) AS age
    FROM employe e
    JOIN grade g ON e.id_grade = g.id_grade
    WHERE TIMESTAMPDIFF(YEAR, e.date_naissance, CURDATE()) >= 65
    ORDER BY e.nom
");
$retraites = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Employés retraités</title>
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
    .btn {
        display: inline-block;
        background: var(--main-green);
        color: #fff;
        padding: 10px 22px;
        border-radius: 6px;
        border: none;
        font-size: 1em;
        font-weight: 500;
        box-shadow: 0 2px 6px rgba(24,24,24,0.06);
        transition: background 0.2s, transform 0.2s;
        cursor: pointer;
        text-decoration: none;
        margin: 10px 8px 0 8px;
    }
    .btn:hover {
        background: #219150;
        transform: translateY(-2px) scale(1.03);
        text-decoration: none;
        color: #fff;
    }
    table {
        border-collapse: collapse;
        width: 100%;
        margin: 20px 0;
        background: #fff;
        box-shadow: 0 2px 8px rgba(24,24,24,0.05);
    }
    th, td {
        border: 1px solid var(--border);
        padding: 12px 18px;
        text-align: left;
    }
    th {
        background: var(--main-green);
        color: #fff;
        font-weight: 600;
    }
    tr:nth-child(even) {
        background: #f3fdf6;
    }
    .back {
        margin-top: 25px;
        display: inline-block;
        color: var(--main-green);
        font-weight: 500;
        text-decoration: none;
        transition: color 0.2s;
    }
    .back:hover {
        color: #219150;
        text-decoration: underline;
    }
    </style>
</head>
<body>
    <div class="container">
        <h1>Liste des employés retraités (âge &ge; 65 ans)</h1>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Date de naissance</th>
                    <th>Âge</th>
                    <th>Date de recrutement</th>
                    <th>Grade</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($retraites as $emp): ?>
                    <tr>
                        <td><?= htmlspecialchars($emp['nom']) ?></td>
                        <td><?= htmlspecialchars($emp['prenom']) ?></td>
                        <td><?= htmlspecialchars($emp['date_naissance']) ?></td>
                        <td><?= htmlspecialchars($emp['age']) ?></td>
                        <td><?= htmlspecialchars($emp['date_recrutement']) ?></td>
                        <td><?= htmlspecialchars($emp['nom_grade']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a class="btn" href="export_retraites.php">Exporter les employés en Excel</a>
        <a class="back" href="page.php">Retour</a>
    </div>
</body>
</html>