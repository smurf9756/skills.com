<?php
include 'db.php';

$bookings = $pdo->query("SELECT * FROM bookings ORDER BY booking_id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Manage Bookings</title>
    <link rel="stylesheet" href="com.css">
</head>

<body>
    <?php include 'admin-dashboard.php'; ?>

    <div class="content">
        <h1>Manage Bookings</h1>

        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Skill</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $b): ?>
                <tr>
                    <td><?= htmlspecialchars($b["user_name"]) ?></td>
                    <td><?= htmlspecialchars($b["skill_name"]) ?></td>
                    <td><?= htmlspecialchars($b["booking_date"]) ?></td>
                    <td><?= htmlspecialchars($b["status"]) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>