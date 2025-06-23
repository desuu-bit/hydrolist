<?php
require_once 'db.php';

if (!isset($_GET['id'])) {
    exit('ID employé manquant.');
}

$id = intval($_GET['id']);

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=fiche_preretraite_'.$id.'.csv');
echo "\xEF\xBB\xBF";

$output = fopen('php://output', 'w');

fputcsv($output, [
    'Nom',
    'Prénom',
    'Date de naissance',
    'Date de recrutement',
    'Grade'
]);

$stmt = $pdo->prepare("
    SELECT e.nom, e.prenom, e.date_naissance, e.date_recrutement, g.nom_grade
    FROM employe e
    JOIN grade g ON e.id_grade = g.id_grade
    WHERE e.id_employe = ?
");
$stmt->execute([$id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    fputcsv($output, [
        $row['nom'],
        $row['prenom'],
        $row['date_naissance'],
        $row['date_recrutement'],
        $row['nom_grade']
    ]);
}

fclose($output);
exit;