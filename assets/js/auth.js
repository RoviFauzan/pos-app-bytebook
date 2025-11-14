import supabase from './supabase-client.js';

const form = document.getElementById('login-form');
const errorBox = document.getElementById('login-error');

async function login(username, password) {
  try {
    const rows = await supabase.fetchRest(
      `admin?username=eq.${encodeURIComponent(username)}&select=id_admin,username,password,nama_admin,id_role`
    );
    if (!rows.length) return false;
    const admin = rows[0];
    const hashed = md5(password);
    if (hashed !== admin.password) return false;

    localStorage.setItem('kasir_session', JSON.stringify({
      id_admin: admin.id_admin,
      username: admin.username,
      nama_admin: admin.nama_admin,
      id_role: admin.id_role,
      time: Date.now()
    }));
    return true;
  } catch (e) {
    console.error('Login error', e);
    return false;
  }
}

if (form) {
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    errorBox.style.display = 'none';
    const fd = new FormData(form);
    const ok = await login(fd.get('username').trim(), fd.get('password').trim());
    if (ok) {
      window.location.href = 'dashboard.html';
    } else {
      errorBox.style.display = 'block';
    }
  });
}

// Auto-redirect if already logged in
if (window.location.pathname.endsWith('/view/login.html') || window.location.pathname.endsWith('login.html')) {
  const sess = localStorage.getItem('kasir_session');
  if (sess) window.location.href = 'dashboard.html';
}
