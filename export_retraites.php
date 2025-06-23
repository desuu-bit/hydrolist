<?php
require_once 'db.php';
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=employes_retraites.csv');
echo "\xEF\xBB\xBF";

$output = fopen('php://output', 'w');


fputcsv($output, [
    'Nom',
    'Prénom',
    'Date de naissance',
    'Date de recrutement',
    'Grade'
]);

$stmt = $pdo->query("
    SELECT 
        e.nom, 
        e.prenom, 
        e.date_naissance, 
        e.date_recrutement, 
        g.nom_grade
    FROM employe e
    JOIN grade g ON e.id_grade = g.id_grade
    WHERE DATE_ADD(e.date_naissance, INTERVAL 65 YEAR) <= CURDATE()
    ORDER BY e.nom
");

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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