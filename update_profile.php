<?php
include 'db.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$fullname = trim($_POST['fullname'] ?? '');
$status = trim($_POST['status'] ?? 'offline');
$image_path = null;

// Handle image upload
if (!empty($_FILES['profile_image']['name'])) {
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) mkdir($targetDir);

    $fileName = time() . "_" . basename($_FILES["profile_image"]["name"]);
    $targetFile = $targetDir . $fileName;

    if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetFile)) {
        $image_path = $targetFile;
    }
}

$query = "UPDATE users SET fullname=?, status=?";
$params = [$fullname, $status];

if ($image_path) {
    $query .= ", profile_image=?";
    $params[] = $image_path;
}

$query .= " WHERE id=?";
$params[] = $user_id;

$stmt = $pdo->prepare($query);

echo json_encode([
    "success" => $stmt->execute($params),
    "message" => "Profile updated"
]);