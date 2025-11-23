document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('profileForm');
  const profilePreview = document.getElementById('profilePreview');
  const profileImageInput = document.getElementById('profileImage');

  // Image Preview
  profileImageInput.addEventListener('change', (event) => {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = (e) => {
        profilePreview.src = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  });

  // AJAX Form Submission
  form.addEventListener('submit', (e) => {
    e.preventDefault(); // prevent normal form submit

    const formData = new FormData(form); // automatically includes file input

    fetch('update_profile.php', {
      method: 'POST',
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert(data.message);
          // Optionally update displayed profile info without reload
        } else {
          alert('Error: ' + data.message);
        }
      })
      .catch(err => {
        console.error(err);
        alert('An unexpected error occurred.');
      });
  });
});
