<?php
include 'db.php';

$users = $pdo->query("SELECT id, fullname, email, role FROM users ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Manage Users</title>
    <link rel="stylesheet" href="com.css">
</head>

<body>
    <?php include 'admin-dashboard.php'; ?>

    <div class="content">
        <h1>Manage Users</h1>

        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['fullname']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= htmlspecialchars($u['role']) ?></td>

                    <td>
                        <a href="admin-edit-user.php?id=<?= $u['id'] ?>">✏ Edit</a> |
                        <a href="admin-delete-user.php?id=<?= $u['id'] ?>"
                            onclick="return confirm('Delete this user?')">🗑 Delete</a> |
                        <a href="admin-message-user.php?id=<?= $u['id'] ?>">💬 Message</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>
</body>

</html>