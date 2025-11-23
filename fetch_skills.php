<?php
header("Content-Type: application/json");
require_once __DIR__ . "/../db.php";

$stmt = $pdo->query("
    SELECT ss.id, ss.skill_name, ss.platform, ss.description,
           u.fullname AS trainer_name, u.id AS trainer_id
    FROM shared_skills ss
    JOIN users u ON ss.user_id = u.id
    WHERE ss.status = 'approved'
");

$skills = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "success" => true,
    "skills" => $skills
]);