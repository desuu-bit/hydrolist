<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
require_once 'db.php';

$grades = $pdo->query("SELECT id_grade, nom_grade FROM grade ORDER BY nom_grade")->fetchAll(PDO::FETCH_ASSOC);

$employes = [];
$selected_grade = $_GET['grade'] ?? '';
$search_id = $_GET['id_employe'] ?? '';

if ($search_id) {
    $stmt = $pdo->prepare("
        SELECT e.id_employe, e.nom, e.prenom, e.date_naissance, e.date_recrutement, g.nom_grade
        FROM employe e
        JOIN grade g ON e.id_grade = g.id_grade
        WHERE e.id_employe = ?
    ");
    $stmt->execute([$search_id]);
    $employes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} elseif ($selected_grade) {
    // Recherche par grade
    $stmt = $pdo->prepare("
        SELECT e.id_employe, e.nom, e.prenom, e.date_naissance, e.date_recrutement, g.nom_grade
        FROM employe e
        JOIN grade g ON e.id_grade = g.id_grade
        WHERE g.id_grade = ?
        ORDER BY e.nom
    ");
    $stmt->execute([$selected_grade]);
    $employes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Employés par grade</title>
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
    form {
        margin-bottom: 30px;
        display: flex;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
    }
    select, input[type="number"] {
        padding: 8px 16px;
        border-radius: 5px;
        border: 1px solid var(--border);
        font-size: 1em;
        margin-right: 10px;
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
        <h1>Employés par grade</h1>
        <form method="get">
            <select name="grade">
                <option value="">-- Choisir un grade --</option>
                <?php foreach ($grades as $grade): ?>
                    <option value="<?= $grade['id_grade'] ?>" <?= $selected_grade == $grade['id_grade'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($grade['nom_grade']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button class="btn" type="submit">Afficher par grade</button>
            <input type="number" name="id_employe" placeholder="Recherche par ID" min="1" style="width:140px;" value="<?= htmlspecialchars($search_id) ?>">
            <button class="btn" type="submit">Rechercher ID</button>
        </form>

        <?php if (($selected_grade || $search_id) && $employes): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Date de naissance</th>
                        <th>Date de recrutement</th>
                        <th>Grade</th>
                        <th>Fiche Excel</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($employes as $emp): ?>
                        <tr>
                            <td><?= htmlspecialchars($emp['id_employe']) ?></td>
                            <td><?= htmlspecialchars($emp['nom']) ?></td>
                            <td><?= htmlspecialchars($emp['prenom']) ?></td>
                            <td><?= htmlspecialchars($emp['date_naissance']) ?></td>
                            <td><?= htmlspecialchars($emp['date_recrutement']) ?></td>
                            <td><?= htmlspecialchars($emp['nom_grade']) ?></td>
                            <td>
                                <a class="btn" href="export_jeunes.php?id=<?= $emp['id_employe'] ?>">Excel</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif (($selected_grade || $search_id)): ?>
            <p>Aucun employé trouvé.</p>
        <?php endif; ?>

        <a class="back" href="page.php">Retour</a>



</html></body>    </div>    </div>
</body>
</html>