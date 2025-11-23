<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>My Profile — Community Skills</title>
    <header>
        <nav>
            <h1>Community Skills Sharing</h1>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="dashboard.php">dashboard</a></li>
            </ul>
        </nav>
    </header>

    <link rel="stylesheet" href="./assets/css/com.css" />
    <style>
    body {
        font-family: "Poppins", sans-serif;
        background: #f6f8fa;
        margin: 0;
        padding: 0;
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
        margin: 0;
        font-size: 22px;
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
        transition: color 0.3s;
    }

    nav ul li a:hover,
    nav ul li a.active {
        color: #ffcc00;
    }

    .container {
        max-width: 800px;
        margin: 50px auto;
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    h1 {
        text-align: center;
        color: #2e7d32;
        margin-bottom: 25px;
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .form-row {
        display: flex;
        flex-direction: column;
    }

    label {
        font-weight: 500;
        margin-bottom: 6px;
    }

    input,
    textarea {
        padding: 10px;
        border-radius: 8px;
        border: 1px solid #ccc;
        font-size: 15px;
    }

    textarea {
        resize: vertical;
        min-height: 100px;
    }

    .profile-pic {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 20px;
        cursor: pointer;
    }

    .profile-pic img {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 10px;
        border: 3px solid #2e7d32;
        transition: transform 0.3s ease;
    }

    .profile-pic img:hover {
        transform: scale(1.05);
    }

    .btn {
        padding: 12px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 600;
        transition: background 0.3s ease;
    }

    .btn-primary {
        background: #2e7d32;
        color: #fff;
    }

    .btn-primary:hover {
        background: #256428;
    }

    .message {
        text-align: center;
        font-weight: 500;
        margin-bottom: 10px;
    }

    @media (max-width: 600px) {
        .container {
            margin: 20px;
            padding: 20px;
        }
    }

    small {
        color: #555;
    }
    </style>
</head>

<body>
    <div class="container">
        <h1>My Profile</h1>
        <div class="message" id="message"></div>

        <form id="profileForm" action="update_profile.php" method="POST" enctype="multipart/form-data">
            <!-- Profile Picture Upload -->
            <div class="profile-pic">
                <label for="profileImage">
                    <img id="profilePreview" src="./assets/images/default-avatar.png" alt="Profile Picture Preview" />
                </label>
                <input type="file" id="profileImage" name="profileImage" accept="image/*" style="display: none" />
            </div>

            <div class="form-row">
                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" name="fullname" placeholder="Enter your full name" required
                    autocomplete="name" maxlength="100" />
            </div>

            <div class="form-row">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="you@example.com" required autocomplete="email"
                    maxlength="100" />
            </div>

            <div class="form-row">
                <label for="phone">Phone</label>
                <input type="tel" id="phone" name="phone" placeholder="07XX XXX XXX" autocomplete="tel"
                    maxlength="15" />
            </div>

            <div class="form-row">
                <label for="about">About Me</label>
                <textarea id="about" name="about"
                    placeholder="Tell us about your background, experience, and what you love doing..."
                    maxlength="300"></textarea>
                <small id="charCount">0/300</small>
            </div>

            <button type="submit" class="btn btn-primary">Save Profile</button>
        </form>
    </div>

    <script>
    // Load current user data via AJAX
    async function loadProfile() {
        const res = await fetch("get_profile.php"); // New file to fetch user data
        const data = await res.json();
        if (data.success) {
            const user = data.user;
            document.getElementById("fullname").value = user.fullname || "";
            document.getElementById("email").value = user.email || "";
            document.getElementById("phone").value = user.phone || "";
            document.getElementById("about").value = user.about || "";
            document.getElementById("charCount").textContent = `${
            user.about?.length || 0
          }/300`;
            const imgSrc = user.image ?
                "./uploads/" + user.image :
                "./assets/images/default-avatar.png";
            document.getElementById("profilePreview").src = imgSrc;
        }
    }

    loadProfile();

    // Image Preview
    const profileInput = document.getElementById("profileImage");
    profileInput.addEventListener("change", function(event) {
        const file = event.target.files[0];
        if (file) {
            if (!file.type.startsWith("image/")) {
                alert("Please upload a valid image file.");
                profileInput.value = "";
                return;
            }
            if (file.size > 2 * 1024 * 1024) {
                alert("File size must be less than 2MB.");
                profileInput.value = "";
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById("profilePreview").src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Character Counter
    const about = document.getElementById("about");
    const charCount = document.getElementById("charCount");
    about.addEventListener("input", () => {
        charCount.textContent = `${about.value.length}/300`;
    });

    // Handle form submission via AJAX
    const form = document.getElementById("profileForm");
    form.addEventListener("submit", async function(e) {
        e.preventDefault();
        const formData = new FormData(form);
        const res = await fetch(form.action, {
            method: "POST",
            body: formData,
        });
        const data = await res.json();
        const msg = document.getElementById("message");
        if (data.success) {
            msg.style.color = "green";
            msg.textContent = data.message;
        } else {
            msg.style.color = "red";
            msg.textContent = data.message || "Update failed!";
        }
    });
    </script>
</body>

</html>