<?php
// booking.php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Book a Skill - Community Skills Sharing</title>
    <link rel="stylesheet" href="com.css" />
    <style>
    .form-container {
        max-width: 500px;
        margin: 40px auto;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        background: #fff;
    }

    label {
        display: block;
        margin-top: 15px;
        font-weight: bold;
    }

    input,
    select,
    textarea {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border-radius: 4px;
        border: 1px solid #ccc;
    }

    button {
        margin-top: 20px;
        padding: 12px 20px;
        background: #4CAF50;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    button:hover {
        background: #45a049;
    }

    .success-message {
        color: green;
        margin-top: 15px;
    }

    .error-message {
        color: red;
        margin-top: 15px;
    }
    </style>
</head>

<body>

    <!-- Navigation -->
    <header>
        <nav>
            <h1>Community Skills Sharing</h1>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="skills.php">Skills</a></li>
                <li><a href="booking.php" class="active">Book a Skill</a></li>
                <li><a href="mybookings.php">My Bookings</a></li>
            </ul>
        </nav>
    </header>

    <!-- Booking Form -->
    <section class="form-container">
        <h2>Book a Skill</h2>
        <form id="bookingForm">

            <label for="skill">Skill Requested:</label>
            <select id="skill" name="skill_id" required>
                <option value="">Loading skills…</option>
            </select>

            <label for="date">Booking Date:</label>
            <input type="date" id="date" name="date" required />

            <label for="notes">Notes / Message:</label>
            <textarea id="notes" name="notes" rows="3" placeholder="Any special requests?"></textarea>

            <button type="submit">Book Now</button>

            <div class="success-message" id="successMsg"></div>
            <div class="error-message" id="errorMsg"></div>
        </form>
    </section>

    <footer>
        <p>&copy; <?= date('Y') ?> Community Skills Sharing | Powered by Samuel Nyaga</p>
    </footer>

    <script>
    const successMsg = document.getElementById('successMsg');
    const errorMsg = document.getElementById('errorMsg');

    // Load skills list
    async function loadSkills() {
        try {
            const res = await fetch('php/fetch_skills.php');
            const data = await res.json();
            const sel = document.getElementById('skill');

            sel.innerHTML = '<option value="">Select a skill</option>';

            if (data.success && data.skills.length) {
                data.skills.forEach(s => {
                    const opt = document.createElement('option');
                    opt.value = s.id;
                    opt.textContent = s.skill_name + (s.trainer_name ? " — " + s.trainer_name : "");
                    sel.appendChild(opt);
                });
            } else {
                sel.innerHTML = '<option value="">No skills available</option>';
            }
        } catch (err) {
            console.error(err);
            document.getElementById('skill').innerHTML =
                '<option value="">Error loading skills</option>';
        }
    }

    // Submit booking
    document.getElementById('bookingForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        successMsg.textContent = '';
        errorMsg.textContent = '';

        const formData = new FormData(e.target);

        try {
            const res = await fetch('php/book_skills.php', {
                method: 'POST',
                body: formData
            });

            const data = await res.json();

            if (data.success) {
                successMsg.textContent =
                    `✅ Booking confirmed! Booking ID: ${data.booking_id}`;
                e.target.reset();
            } else {
                errorMsg.textContent = `⚠️ ${data.message}`;
            }

        } catch (err) {
            console.error(err);
            errorMsg.textContent = '⚠️ An unexpected error occurred.';
        }
    });

    loadSkills();
    </script>

</body>

</html>