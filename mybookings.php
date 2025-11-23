<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

$user_id = intval($_SESSION['user_id']);
$user_id = intval($_SESSION['user_id']);

$stmt = $pdo->prepare("
    SELECT b.*, 
           s.skill_name AS skill_title, 
           u.fullname AS provider_name
    FROM bookings b
    JOIN shared_skills s ON b.skill_id = s.id
    JOIN users u ON s.id = u.id
    WHERE b.requester_id = ?
    ORDER BY b.booking_date DESC, b.created_at DESC
");
$stmt->execute([$user_id]);
$bookings = $stmt->fetchAll();


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>My Bookings</title>
    <link rel="stylesheet" href="com.css" />
</head>

<body>
    <header>
        <nav>
            <h1>Community Skills Sharing</h1>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="skills.php">Skills</a></li>
                <li><a href="booking.php">Booking</a></li>
                <li><a href="dashboard.php">dashboard</a></li>
                <li><a href="mybookings.php" class="active">My Bookings</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main style="max-width: 900px; margin: 30px auto">
        <h2>My Bookings</h2>
        <?php if (empty($bookings)): ?>
        <p>You have no bookings yet.</p>
        <?php else: ?>
        <table style="width: 100%; border-collapse: collapse">
            <thead>
                <tr>
                    <th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">Booking ID</th>
                    <th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">Skill</th>
                    <th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">Date</th>
                    <th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">Provider</th>
                    <th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">Notes</th>
                    <th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">Created</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $b): ?>
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #eee"><?= htmlspecialchars($b['id']) ?></td>
                    <td style="padding: 8px; border-bottom: 1px solid #eee"><?= htmlspecialchars($b['skill_title']) ?>
                    </td>
                    <td style="padding: 8px; border-bottom: 1px solid #eee"><?= htmlspecialchars($b['booking_date']) ?>
                    </td>
                    <td style="padding: 8px; border-bottom: 1px solid #eee">
                        <?= htmlspecialchars($b['provider_name'] ?? 'N/A') ?></td>
                    <td style="padding: 8px; border-bottom: 1px solid #eee"><?= nl2br(htmlspecialchars($b['notes'])) ?>
                    </td>
                    <td style="padding: 8px; border-bottom: 1px solid #eee"><?= htmlspecialchars($b['created_at']) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </main>
</body>

</html>