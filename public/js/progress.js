// public/js/progress.js
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('registerForm');
  const progressBar = document.getElementById('progressBar');
  const inputs = form.querySelectorAll('[data-progress]');

  inputs.forEach(input => input.addEventListener('input', updateProgress));

  function updateProgress() {
    let total = 0;
    inputs.forEach(i => {
      if (i.value.trim() !== '') {
        total += parseInt(i.getAttribute('data-progress'));
      }
    });
    total = Math.min(total, 100);
    progressBar.style.width = `${total}%`;
    progressBar.textContent = `${total}%`;
  }
});
