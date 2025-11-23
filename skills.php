<?php
session_start();
include 'db.php';

// Fetch only approved skills
$skills = $pdo->query("
    SELECT id, trainer_name, skill_name, platform, description, image_path, created_at
    FROM shared_skills
    WHERE status = 'approved'
    ORDER BY id DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Skills - Community Skills Sharing</title>
    <link rel="stylesheet" href="com.css" />
    <style>
    body {
        font-family: "Poppins", sans-serif;
        margin: 0;
        background-color: #f5f7fa;
    }

    nav {
        background-color: #004aad;
        color: white;
        padding: 15px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    nav h1 {
        font-size: 22px;
        margin: 0;
    }

    nav ul {
        list-style: none;
        display: flex;
        gap: 20px;
        margin: 0;
    }

    nav ul li a {
        color: white;
        text-decoration: none;
        font-weight: 500;
    }

    nav ul li a:hover,
    nav ul li a.active {
        color: #ffcc00;
    }

    .skills-section {
        text-align: center;
        padding: 40px 20px;
    }

    .skills-section h2 {
        font-size: 28px;
        color: #004aad;
        margin-bottom: 20px;
    }

    .share-btn {
        background-color: #ffcc00;
        color: #004aad;
        font-weight: 600;
        padding: 12px 25px;
        border-radius: 8px;
        text-decoration: none;
        margin-bottom: 30px;
        display: inline-block;
    }

    .skills-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        max-width: 1300px;
        margin: 0 auto;
    }

    .skill-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: .3s;
    }

    .skill-card:hover {
        transform: translateY(-8px);
    }

    .skill-img {
        height: 180px;
        background-size: cover;
        background-position: center;
    }

    .skill-content {
        padding: 20px;
        text-align: center;
    }

    .skill-content h3 {
        color: #004aad;
    }

    .btn {
        background-color: #004aad;
        color: #fff;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        margin-top: 15px;
        display: inline-block;
    }

    /* Review Section */
    .review-section {
        background: #fff;
        margin-top: 20px;
        padding: 10px;
        border-radius: 10px;
    }

    .review-section input,
    .review-section select,
    .review-section textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
    }

    .reviews-list {
        margin-top: 10px;
        border-top: 1px solid #ddd;
        padding-top: 10px;
    }

    .review {
        border-bottom: 1px solid #eee;
        padding: 8px 0;
    }

    .review strong {
        color: #004aad;
    }

    footer {
        text-align: center;
        padding: 20px;
        background: #004aad;
        color: white;
    }
    </style>
</head>

<body>
    <header>
        <nav>
            <h1>Community Skills Sharing</h1>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="skills.php" class="active">Skills</a></li>
                <li><a href="share_skill.php">Share Skill</a></li>
                <li><a href="dashboard.php">dashboard</a></li>
                <li><a href="messages.php">Messages</a></li>
            </ul>
        </nav>
    </header>

    <section class="skills-section">
        <h2>Available Skills</h2>
        <a href="share_skill.php" class="share-btn">Are you a Trainer? Share a Skill</a>

        <div class="skills-grid">
            <?php foreach ($skills as $s): ?>
            <div class="skill-card">
                <div class="skill-img"
                    style="background-image:url('<?= htmlspecialchars("./" . ($s['image_path'] ?: "default.jpg")) ?>')">
                </div>


                <div class="skill-content">
                    <h3><?= htmlspecialchars($s["skill_name"]) ?></h3>
                    <p><strong>Trainer:</strong> <?= htmlspecialchars($s["trainer_name"]) ?></p>
                    <p><strong>Platform:</strong> <?= htmlspecialchars($s["platform"]) ?></p>
                    <p><?= nl2br(htmlspecialchars($s["description"])) ?></p>

                    <a href="booking.php?skill_id=<?= $s['id'] ?>" class="btn">Book Now</a>

                    <!-- Review Section -->
                    <div class="review-section" data-skill="<?= $s['id'] ?>">
                        <h4>Rate & Review</h4>
                        <input type="text" class="review-name" placeholder="Your Name" required>
                        <select class="review-rating">
                            <option value="">Rating</option>
                            <option value="1">⭐</option>
                            <option value="2">⭐⭐</option>
                            <option value="3">⭐⭐⭐</option>
                            <option value="4">⭐⭐⭐⭐</option>
                            <option value="5">⭐⭐⭐⭐⭐</option>
                        </select>
                        <textarea class="review-text" rows="2" placeholder="Your review..." required></textarea>
                        <button class="btn submitReview">Submit</button>

                        <div class="reviews-list" id="reviews-<?= $s['id'] ?>"></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <footer>
        <p>&copy; 2025 Community Skills Sharing | Powered by Samuel Nyaga</p>
    </footer>

    <script>
    // Load and save reviews using localStorage
    document.querySelectorAll(".submitReview").forEach(btn => {
        btn.addEventListener("click", function() {

            const card = this.parentElement;
            const skillId = card.getAttribute("data-skill");

            const name = card.querySelector(".review-name").value.trim();
            const rating = card.querySelector(".review-rating").value;
            const text = card.querySelector(".review-text").value.trim();

            if (!name || !rating || !text) {
                alert("Please fill all fields");
                return;
            }

            const review = {
                name,
                rating,
                text,
                time: new Date().toLocaleString()
            };

            let allReviews = JSON.parse(localStorage.getItem("reviews_" + skillId)) || [];
            allReviews.unshift(review);
            localStorage.setItem("reviews_" + skillId, JSON.stringify(allReviews));

            card.querySelector(".review-name").value = "";
            card.querySelector(".review-rating").value = "";
            card.querySelector(".review-text").value = "";

            loadReviews(skillId);
        });
    });

    // Load reviews
    function loadReviews(skillId) {
        const container = document.getElementById("reviews-" + skillId);
        container.innerHTML = "";

        let reviews = JSON.parse(localStorage.getItem("reviews_" + skillId)) || [];

        reviews.forEach(r => {
            const div = document.createElement("div");
            div.classList.add("review");
            div.innerHTML =
                `<strong>${r.name}</strong> (${r.rating}⭐) <br> ${r.text} <br><small>${r.time}</small>`;
            container.appendChild(div);
        });
    }

    // Load reviews for all cards
    document.querySelectorAll(".review-section").forEach(section => {
        let skillId = section.getAttribute("data-skill");
        loadReviews(skillId);
    });
    </script>

</body>

</html>