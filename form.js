document.addEventListener('DOMContentLoaded', function () {
  const contactForm = document.getElementById('contactForm');

  contactForm.addEventListener('submit', async function (e) {
    e.preventDefault();

    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const phone = document.getElementById('phone').value;
    const message = document.getElementById('message').value;

    // Basic email validation for illustrative purposes
    const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    const phoneRegex = /^\(?\d{3}\)?[-. ]?\d{3}[-. ]?\d{4}$/;
    if (
      !name ||
      !emailRegex.test(email) ||
      !phoneRegex.test(phone) ||
      !message
    ) {
      alert('Please ensure all fields are filled correctly');
      return;
    }

    const formData = new FormData();
    formData.append('name', name);
    formData.append('email', email);
    formData.append('phone', phone);
    formData.append('message', message);

    try {
      const response = await fetch('process.php', {
        method: 'POST',
        body: formData,
      });

      if (!response.ok) {
        throw new Error('Network response was not ok');
      }

      const data = await response.text();
      // handle or display the data as needed
      contactForm.reset();
      window.location.href = 'submit.html';
    } catch (error) {
      console.error('Error:', error);
      // Handle the error in a user-friendly way, e.g., show a message on the UI
    }
  });
});
