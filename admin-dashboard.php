<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard - Community Skills Sharing</title>
    <link rel="stylesheet" href="com.css" />
    <style>
    body {
        display: flex;
        font-family: "Poppins", sans-serif;
        margin: 0;
    }

    .sidebar {
        width: 220px;
        background: #007bff;
        color: #fff;
        height: 100vh;
        padding: 20px;
    }

    .sidebar h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    .sidebar a {
        display: block;
        color: #fff;
        padding: 10px;
        text-decoration: none;
        border-radius: 8px;
        margin-bottom: 10px;
    }

    .sidebar a:hover {
        background: #0056b3;
    }

    .content {
        flex: 1;
        padding: 30px;
        background: #f8f9fb;
    }

    .card {
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin: 10px;
        text-align: center;
        width: 180px;
    }

    .dashboard {
        display: flex;
        flex-wrap: wrap;
    }
    </style>
</head>

<body onload="checkAdminSession(); loadStats();">
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="admin-dashboard.php">Dashboard</a>
        <a href="admin-actions.php"> actions</a>
        <a href="admin-users.php"> Manage Users</a>
        <a href="admin-skills.php"> Manage Skills</a>
        <a href="admin-bookings.php"> Manage Bookings</a>
        <a href="dashboard.php"> users Panel</a>
        <a href="user-message.php">📩 Messages</a>

        <a href="#" onclick="logoutAdmin()">Logout</a>
    </div>

    <div class="content">
        <h1>Welcome, Admin</h1>
        <div class="dashboard">
            <div class="card">
                <h3>Total Users</h3>
                <p id="totalUsers">0</p>
            </div>
            <div class="card">
                <h3>Total Skills</h3>
                <p id="totalSkills">0</p>
            </div>
            <div class="card">
                <h3>Total Bookings</h3>
                <p id="totalBookings">0</p>
            </div>
            <div class="card">
                <h3>Active Trainers</h3>
                <p id="activeTrainers">0</p>
            </div>
        </div>
    </div>

    <script>
    function checkAdminSession() {
        if (localStorage.getItem("isAdminLoggedIn") !== "true") {
            alert("Access denied. Please login as Admin.");
            window.location.href = "admin-login.php";
        }
    }

    function logoutAdmin() {
        localStorage.removeItem("isAdminLoggedIn");
        alert("Logged out successfully!");
        window.location.href = "admin-login.php";
    }

    function getData(key) {
        return JSON.parse(localStorage.getItem(key)) || [];
    }

    function loadStats() {
        const users = getData("users");
        const skills = getData("skills");
        const bookings = getData("bookings");

        document.getElementById("totalUsers").textContent = users.length;
        document.getElementById("totalSkills").textContent = skills.length;
        document.getElementById("totalBookings").textContent = bookings.length;

        const trainers = users.filter((u) => u.role === "trainer").length;
        document.getElementById("activeTrainers").textContent = trainers;
    }
    </script>
</body>

</html>