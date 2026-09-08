const form = document.getElementById('loginForm');
const submitBtn = document.getElementById('submitBtn');
const toast = document.getElementById('toast');
const togglePw = document.getElementById('togglePw');
const pwInput = document.getElementById('password');

togglePw.addEventListener('click', () => {
  const isPw = pwInput.type === 'password';
  pwInput.type = isPw ? 'text' : 'password';
  togglePw.textContent = isPw ? 'SEMBUNYI' : 'TAMPIL';
});

function setError(id, message) {
  const input = document.getElementById(id);
  const err = document.getElementById('err-' + id);
  err.textContent = message || '';
  input.classList.toggle('err', !!message);
}

form.addEventListener('submit', (e) => {
  e.preventDefault();

  const username = document.getElementById('username').value.trim();
  const password = pwInput.value.trim();

  let valid = true;
  setError('username', '');
  setError('password', '');

  if (!username) {
    setError('username', 'Username atau email wajib diisi');
    valid = false;
  }
  if (!password) {
    setError('password', 'Kata sandi wajib diisi');
    valid = false;
  } else if (password.length < 8) {
    setError('password', 'Kata sandi minimal 8 karakter');
    valid = false;
  }

  if (!valid) return;

  // Simulasi proses autentikasi (belum terhubung ke backend)
  submitBtn.classList.add('loading');
  submitBtn.disabled = true;

  setTimeout(() => {
    submitBtn.classList.remove('loading');
    submitBtn.disabled = false;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3000);
  }, 1200);
});