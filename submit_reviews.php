<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $skill_name = $conn->real_escape_string($_POST['skill_name']);
  $username = $conn->real_escape_string($_POST['username']);
  $rating = (int) $_POST['rating'];
  $review = $conn->real_escape_string($_POST['review']);

  $sql = "INSERT INTO reviews (skill_name, username, rating, review)
          VALUES ('$skill_name', '$username', '$rating', '$review')";
          
  if ($conn->query($sql)) {
    echo "success";
  } else {
    echo "error: " . $conn->error;
  }
}
$conn->close();
?>