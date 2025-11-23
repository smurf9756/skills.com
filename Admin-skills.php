<?php
include 'db.php';

// Handle approval
if (isset($_GET["approve"])) {
  $id = $_GET["approve"];
  $pdo->prepare("UPDATE shared_skills SET status='approved' WHERE id=?")->execute([$id]);
  header("Location: admin-skills.php");
  exit();
}

// Handle rejection
if (isset($_GET["reject"])) {
  $id = $_GET["reject"];
  $pdo->prepare("UPDATE shared_skills SET status='rejected' WHERE id=?")->execute([$id]);
  header("Location: admin-skills.php");
  exit();
}

$skills = $pdo->query("SELECT * FROM shared_skills ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Manage Skills</title>
    <link rel="stylesheet" href="com.css">
</head>

<body>
    <?php include 'admin-dashboard.php'; ?>

    <div class="content">
        <h1>Manage Skills</h1>

        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>Trainer</th>
                    <th>Skill</th>
                    <th>Platform</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($skills as $s): ?>
                <tr>
                    <td><?= htmlspecialchars($s["trainer_name"]) ?></td>
                    <td><?= htmlspecialchars($s["skill_name"]) ?></td>
                    <td><?= htmlspecialchars($s["platform"]) ?></td>
                    <td><strong><?= $s["status"] ?></strong></td>
                    <td>
                        <?php if ($s["status"] === "pending"): ?>
                        <a href="?approve=<?= $s["id"] ?>">✔ Approve</a> |
                        <a href="?reject=<?= $s["id"] ?>">❌ Reject</a>
                        <?php else: ?>
                        No Action
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>