<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="content-wrapper">
  <!-- PAGE HEADER -->
  <div class="page-header">
    <div class="header-content">
      <h1>Daftar Buku</h1>
      <a href="<?= base_url('/books/create') ?>" class="btn btn-primary">Tambah Buku</a>
    </div>
  </div>

  <!-- FILTERS -->
  <div class="filters-card">
    <div class="filters-grid">
      <div class="filter-item">
        <input id="q" type="text" class="form-control" placeholder="Cari judul, pengarang, kategori">
      </div>
      
      <!-- DROPDOWN KATEGORI -->
      <div class="filter-item">
        <div class="custom-dropdown" id="kategoriDropdown">
          <div class="dropdown-toggle">
            <span class="dropdown-label">Semua Kategori</span>
            <svg class="dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
            </svg>
          </div>
          <div class="dropdown-menu">
            <div class="dropdown-item selected" data-value="">Semua Kategori</div>
            <div class="dropdown-item" data-value="Fiksi">Fiksi</div>
            <div class="dropdown-item" data-value="Non-Fiksi">Non-Fiksi</div>
            <div class="dropdown-item" data-value="Komik">Komik</div>
            <div class="dropdown-item" data-value="Teknologi">Teknologi</div>
            <div class="dropdown-item" data-value="Bisnis">Bisnis</div>
          </div>
        </div>
        <input type="hidden" id="kategori" value="">
      </div>

      <!-- DROPDOWN STATUS -->
      <div class="filter-item">
        <div class="custom-dropdown" id="statusDropdown">
          <div class="dropdown-toggle">
            <span class="dropdown-label">Semua Status</span>
            <svg class="dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
            </svg>
          </div>
          <div class="dropdown-menu">
            <div class="dropdown-item selected" data-value="">Semua Status</div>
            <div class="dropdown-item" data-value="Tersedia">Tersedia</div>
            <div class="dropdown-item" data-value="Dipinjam">Dipinjam</div>
          </div>
        </div>
        <input type="hidden" id="status" value="">
      </div>
    </div>
  </div>

  <!-- TABLE -->
  <div class="table-card">
    <div class="table-wrapper">
      <table class="data-table">
        <thead>
          <tr>
            <th class="col-id">ID</th>
            <th class="col-judul">Judul</th>
            <th class="col-pengarang">Pengarang</th>
            <th class="col-penerbit">Penerbit</th>
            <th class="col-tahun">Tahun</th>
            <th class="col-halaman">Halaman</th>
            <th class="col-kategori">Kategori</th>
            <th class="col-isbn">ISBN</th>
            <th class="col-status">Status</th>
            <th class="col-peminjam">Peminjam</th>
            <th class="col-aksi">Aksi</th>
          </tr>
        </thead>
        <tbody id="booksBody">
          <tr><td colspan="11" class="text-center py-4">Loading data...</td></tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- PAGINATION -->
  <nav id="paginationWrap" aria-label="Pagination"></nav>
</div>

<!-- MODAL PINJAM -->
<div class="modal fade" id="borrowModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <form id="borrowForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Pinjam Buku</h5>
        </div>
        <div class="modal-body">
          <input type="hidden" id="borrowBookId">
          <label class="form-label">Nama Peminjam</label>
          <input id="borrowerName" class="form-control" required placeholder="Masukkan nama peminjam">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success">Konfirmasi</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- MODAL HAPUS -->
<div class="modal fade" id="deleteModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger">
        <h5 class="modal-title">Konfirmasi Hapus</h5>
      </div>
      <div class="modal-body">
        <p class="mb-2">
          Yakin ingin menghapus buku <strong id="deleteBookTitle"></strong>?
        </p>
        <p class="text-muted small mb-0">
          Tindakan ini tidak dapat dibatalkan.
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Hapus</button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL KEMBALIKAN -->
<div class="modal fade" id="returnModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title">Konfirmasi Pengembalian</h5>
      </div>
      <div class="modal-body">
        <p class="mb-2">
          Yakin ingin mengembalikan buku <strong id="returnBookTitle"></strong>?
        </p>
        <p class="text-muted small mb-0">
          Peminjam: <strong id="returnBorrower"></strong>
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-warning" id="confirmReturnBtn">Kembalikan</button>
      </div>
    </div>
  </div>
</div>

<script>
const apiUrl = '<?= base_url('/api-front/books') ?>';
let currentPage = 1;
let borrowModalInstance, deleteModalInstance, returnModalInstance;
let deleteBookId, returnBookId;

function escapeHtml(s){
  return s ? String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"'"}[c])) : '';
}

async function fetchBooks(page = 1) {
  currentPage = page;
  const q        = document.getElementById('q').value || '';
  const kategori = document.getElementById('kategori').value || '';
  const status   = document.getElementById('status').value || '';
  const params   = new URLSearchParams({ page, q, kategori, status, per_page: 10 });

  document.getElementById('booksBody').innerHTML = '<tr><td colspan="11" class="text-center py-4">Loading data...</td></tr>';

  try {
    const res = await fetch(apiUrl + '?' + params, { credentials: 'same-origin' });
    if (!res.ok) throw new Error('Network error');
    renderTable(await res.json());
  } catch (err) {
    document.getElementById('booksBody').innerHTML = '<tr><td colspan="11" class="text-center text-danger py-4">Gagal loading data</td></tr>';
    document.getElementById('paginationWrap').innerHTML = '';
    if (window.Toast) Toast.error('Gagal loading data dari server');
  }
}

function renderTable(payload) {
  const body  = document.getElementById('booksBody');
  const books = Array.isArray(payload) ? payload : (payload.data || []);

  if (!books.length) {
    body.innerHTML = '<tr><td colspan="11" class="text-center py-4">Tidak ada data</td></tr>';
    document.getElementById('paginationWrap').innerHTML = '';
    return;
  }

  body.innerHTML = books.map(b => `
    <tr>
      <td class="col-id">${b.id}</td>
      <td class="col-judul"><a href="<?= base_url('/books') ?>/${b.id}" class="book-link">${escapeHtml(b.judul)}</a></td>
      <td class="col-pengarang">${escapeHtml(b.pengarang || '-')}</td>
      <td class="col-penerbit">${escapeHtml(b.penerbit || '-')}</td>
      <td class="col-tahun">${escapeHtml(b.tahun_terbit ?? '-')}</td>
      <td class="col-halaman">${escapeHtml(b.jumlah_halaman ?? '-')}</td>
      <td class="col-kategori">${escapeHtml(b.kategori || '-')}</td>
      <td class="col-isbn">${escapeHtml(b.isbn || '-')}</td>
      <td class="col-status"><span class="badge-pill ${b.status === 'Tersedia' ? 'badge-success' : 'badge-warning'}">${escapeHtml(b.status || '-')}</span></td>
      <td class="col-peminjam">${escapeHtml(b.borrowed_by || '-')}</td>
      <td class="col-aksi">
        <div class="action-buttons">
          <button class="btn-action btn-edit" onclick="window.location.href='<?= base_url('/books') ?>/${b.id}/edit'">Edit</button>
          <button class="btn-action btn-delete" onclick="openDeleteModal(${b.id}, '${escapeHtml(b.judul).replace(/'/g, "\\'")}')">Hapus</button>
          ${b.status === 'Tersedia' ? `<button class="btn-action btn-borrow" onclick="openBorrowModal(${b.id}, '${escapeHtml(b.judul).replace(/'/g, "\\'")}')">Pinjam</button>` : `<button class="btn-action btn-return" onclick="openReturnModal(${b.id}, '${escapeHtml(b.judul).replace(/'/g, "\\'")}','${escapeHtml(b.borrowed_by || '').replace(/'/g, "\\'")}')">Kembalikan</button>`}
        </div>
      </td>
    </tr>
  `).join('');

  const meta = payload.meta || {};
  const lastPage = meta.last_page ?? Math.max(1, Math.ceil((meta.total ?? books.length) / (meta.per_page ?? 10)));

  if (lastPage > 1) {
    const pages = [];
    if (currentPage > 1) pages.push(`<li class="page-item"><button class="page-link" onclick="fetchBooks(${currentPage - 1})">‹ Prev</button></li>`);
    for (let p = 1; p <= lastPage; p++) pages.push(`<li class="page-item ${p === currentPage ? 'active' : ''}"><button class="page-link" onclick="fetchBooks(${p})">${p}</button></li>`);
    if (currentPage < lastPage) pages.push(`<li class="page-item"><button class="page-link" onclick="fetchBooks(${currentPage + 1})">Next ›</button></li>`);
    document.getElementById('paginationWrap').innerHTML = `<ul class="pagination">${pages.join('')}</ul>`;
  } else {
    document.getElementById('paginationWrap').innerHTML = '';
  }
}

function debounce(fn, wait = 300) {
  let t;
  return function (...args) { clearTimeout(t); t = setTimeout(() => fn.apply(this, args), wait); };
}

function ensureModals() {
  if (window.bootstrap && bootstrap.Modal) {
    if (!borrowModalInstance) borrowModalInstance = new bootstrap.Modal(document.getElementById('borrowModal'));
    if (!deleteModalInstance) deleteModalInstance = new bootstrap.Modal(document.getElementById('deleteModal'));
    if (!returnModalInstance) returnModalInstance = new bootstrap.Modal(document.getElementById('returnModal'));
  }
}

function openBorrowModal(id, title) {
  ensureModals();
  document.getElementById('borrowBookId').value = id;
  document.getElementById('borrowerName').value = '';
  borrowModalInstance.show();
}

function openDeleteModal(id, title) {
  ensureModals();
  deleteBookId = id;
  document.getElementById('deleteBookTitle').textContent = title;
  deleteModalInstance.show();
}

function openReturnModal(id, title, borrower) {
  ensureModals();
  returnBookId = id;
  document.getElementById('returnBookTitle').textContent = title;
  document.getElementById('returnBorrower').textContent = borrower || '-';
  returnModalInstance.show();
}

document.getElementById('borrowForm').addEventListener('submit', async function (e) {
  e.preventDefault();
  const id = document.getElementById('borrowBookId').value;
  const name = document.getElementById('borrowerName').value.trim();
  if (!name) { if (window.Toast) Toast.warning('Masukkan nama peminjam'); return; }
  if (window.Toast) Toast.info(`Memproses peminjaman...`);
  try {
    const res = await fetch('<?= base_url('/books') ?>/' + id + '/borrow', {
      method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ borrowed_by: name }), credentials: 'same-origin'
    });
    if (res.ok || res.redirected) {
      if (window.Toast) Toast.success(`Buku berhasil dipinjam oleh ${name}`);
      borrowModalInstance.hide();
      setTimeout(() => (res.redirected ? window.location.reload() : fetchBooks(currentPage)), 1000);
    } else { if (window.Toast) Toast.error('Gagal meminjam buku'); }
  } catch (err) { if (window.Toast) Toast.error('Terjadi kesalahan'); }
});

document.getElementById('confirmDeleteBtn').addEventListener('click', async function () {
  if (!deleteBookId) return;
  if (window.Toast) Toast.info('Menghapus buku...');
  deleteModalInstance.hide();
  try {
    const res = await fetch('<?= base_url('/books') ?>/' + deleteBookId + '/delete', { credentials: 'same-origin' });
    if (res.ok || res.redirected) {
      if (window.Toast) Toast.success('Buku berhasil dihapus');
      setTimeout(() => (res.redirected ? window.location.reload() : fetchBooks(currentPage)), 1000);
    } else { if (window.Toast) Toast.error('Gagal menghapus buku'); }
  } catch (err) { if (window.Toast) Toast.error('Terjadi kesalahan'); }
});

document.getElementById('confirmReturnBtn').addEventListener('click', async function () {
  if (!returnBookId) return;
  if (window.Toast) Toast.info('Memproses pengembalian...');
  returnModalInstance.hide();
  try {
    const res = await fetch('<?= base_url('/books') ?>/' + returnBookId + '/return', { method: 'POST', credentials: 'same-origin' });
    if (res.ok || res.redirected) {
      if (window.Toast) Toast.success('Buku berhasil dikembalikan');
      setTimeout(() => (res.redirected ? window.location.reload() : fetchBooks(currentPage)), 1000);
    } else { if (window.Toast) Toast.error('Gagal mengembalikan buku'); }
  } catch (err) { if (window.Toast) Toast.error('Terjadi kesalahan'); }
});

document.addEventListener('DOMContentLoaded', function () {
  // Search
  document.getElementById('q').addEventListener('input', debounce(() => fetchBooks(1), 300));
  
  // Custom Dropdowns
  document.querySelectorAll('.custom-dropdown').forEach(dropdown => {
    const toggle = dropdown.querySelector('.dropdown-toggle');
    const menu = dropdown.querySelector('.dropdown-menu');
    const label = dropdown.querySelector('.dropdown-label');
    const items = dropdown.querySelectorAll('.dropdown-item');
    
    toggle.addEventListener('click', function(e) {
      e.preventDefault(); e.stopPropagation();
      document.querySelectorAll('.custom-dropdown').forEach(other => {
        if (other !== dropdown) {
          other.querySelector('.dropdown-toggle').classList.remove('active');
          other.querySelector('.dropdown-menu').classList.remove('show');
        }
      });
      toggle.classList.toggle('active');
      menu.classList.toggle('show');
    });

    items.forEach(item => {
      item.addEventListener('click', function(e) {
        e.stopPropagation();
        const value = item.dataset.value;
        const text = item.textContent;
        items.forEach(i => i.classList.remove('selected'));
        item.classList.add('selected');
        label.textContent = text;
        toggle.classList.remove('active');
        menu.classList.remove('show');
        if (dropdown.id === 'kategoriDropdown') { document.getElementById('kategori').value = value; fetchBooks(1); }
        else if (dropdown.id === 'statusDropdown') { document.getElementById('status').value = value; fetchBooks(1); }
      });
    });
  });

  document.addEventListener('click', function(e) {
    if (!e.target.closest('.custom-dropdown')) {
      document.querySelectorAll('.dropdown-toggle').forEach(t => t.classList.remove('active'));
      document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.remove('show'));
    }
  });
  
  fetchBooks(1);
  ensureModals();
  if (window.Toast) Toast.success('Berhasil loading data buku');
});
</script>

<?= $this->endSection() ?>
