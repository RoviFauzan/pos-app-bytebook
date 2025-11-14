<footer class="footer">
    <div class="d-sm-flex justify-content-center justify-content-sm-between">
      <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2025 <a href="#">Kelompok 4</a>. All rights reserved.</span>
      <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Made by Kelompok 4 <i class="mdi mdi-heart text-danger"></i></span>
    </div>
</footer>

<!-- Modal Fix Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle modal close and reset forms
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('hidden.bs.modal', function() {
            const forms = this.querySelectorAll('form');
            forms.forEach(form => form.reset());
        });
    });
    
    // Fix for password toggle buttons
    const togglePasswordBtns = document.querySelectorAll('[id^="togglePassword"]');
    togglePasswordBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const passwordField = this.closest('.input-group').querySelector('input[type="password"]');
            const eyeIcon = this.querySelector('i');
            
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            
            if (eyeIcon) {
                if (type === 'password') {
                    eyeIcon.classList.remove('mdi-eye');
                    eyeIcon.classList.add('mdi-eye-off');
                } else {
                    eyeIcon.classList.remove('mdi-eye-off');
                    eyeIcon.classList.add('mdi-eye');
                }
            }
        });
    });
});
</script>