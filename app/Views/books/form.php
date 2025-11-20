<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php $mode = $mode ?? 'create';
$book = $book ?? null; ?>

<div class="form-container">
  <div class="form-card">
    <h2><?= $mode === 'create' ? 'Tambah Buku' : 'Ubah Buku' ?></h2>

    <form method="post" action="<?= $mode === 'create' ? base_url('/books') : base_url('/books/' . $book['id']) ?>">

      <div class="form-group">
        <label class="form-label">Judul</label>
        <input name="judul" class="form-control" value="<?= esc($book['judul'] ?? old('judul')) ?>" required>
      </div>

      <div class="form-group">
        <label class="form-label">Pengarang</label>
        <input name="pengarang" class="form-control" value="<?= esc($book['pengarang'] ?? old('pengarang')) ?>" required>
      </div>

      <div class="form-group">
        <label class="form-label">Penerbit</label>
        <input name="penerbit" class="form-control" value="<?= esc($book['penerbit'] ?? old('penerbit')) ?>">
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Tahun Terbit</label>
          <input name="tahun_terbit" type="number" class="form-control" value="<?= esc($book['tahun_terbit'] ?? old('tahun_terbit')) ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Jumlah Halaman</label>
          <input name="jumlah_halaman" type="number" class="form-control" value="<?= esc($book['jumlah_halaman'] ?? old('jumlah_halaman')) ?>">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Kategori</label>
        <select name="kategori" class="form-select" required>
          <option value="">-- Pilih Kategori --</option>
          <option value="Fiksi" <?= (isset($book['kategori']) && $book['kategori'] == 'Fiksi') ? 'selected' : '' ?>>Fiksi</option>
          <option value="Non-Fiksi" <?= (isset($book['kategori']) && $book['kategori'] == 'Non-Fiksi') ? 'selected' : '' ?>>Non-Fiksi</option>
          <option value="Komik" <?= (isset($book['kategori']) && $book['kategori'] == 'Komik') ? 'selected' : '' ?>>Komik</option>
          <option value="Teknologi" <?= (isset($book['kategori']) && $book['kategori'] == 'Teknologi') ? 'selected' : '' ?>>Teknologi</option>
          <option value="Bisnis" <?= (isset($book['kategori']) && $book['kategori'] == 'Bisnis') ? 'selected' : '' ?>>Bisnis</option>
        </select>
      </div>

      <div class="form-group">
        <label class="form-label">ISBN</label>
        <input name="isbn" class="form-control" value="<?= esc($book['isbn'] ?? old('isbn')) ?>" required>
      </div>

      <div class="form-group">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <option value="Tersedia" <?= (isset($book['status']) && $book['status'] == 'Tersedia') ? 'selected' : '' ?>>Tersedia</option>
          <option value="Dipinjam" <?= (isset($book['status']) && $book['status'] == 'Dipinjam') ? 'selected' : '' ?>>Dipinjam</option>
        </select>
      </div>

      <div class="form-group">
        <label class="form-label">Nama Peminjam (jika dipinjam)</label>
        <input name="borrowed_by" class="form-control" value="<?= esc($book['borrowed_by'] ?? old('borrowed_by')) ?>">
      </div>

      <div class="form-actions">
        <a class="btn-cancel" href="<?= base_url('/books') ?>">Batal</a>
        <button type="submit" class="btn-update">
          <?= $mode === 'create' ? 'Simpan' : 'Perbarui' ?>
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const mode = '<?= $mode ?>';

    form.addEventListener('submit', async function(e) {
      e.preventDefault();

      const formData = new FormData(form);
      const action = form.getAttribute('action');
      const message = mode === 'create' ? 'Menyimpan buku...' : 'Memperbarui buku...';
      const successMessage = mode === 'create' ? 'Buku berhasil ditambahkan!' : 'Buku berhasil diperbarui!';

      if (window.Toast) Toast.info(message);

      try {
        const response = await fetch(action, {
          method: 'POST',
          body: formData,
          credentials: 'same-origin'
        });

        if (response.ok || response.redirected) {
          if (window.Toast) Toast.success(successMessage);
          setTimeout(() => {
            window.location.href = '<?= base_url('/books') ?>';
          }, 1500);
        } else {
          if (window.Toast) Toast.error('Terjadi kesalahan, silakan coba lagi');
        }
      } catch (error) {
        if (window.Toast) Toast.error('Terjadi error koneksi');
        console.error(error);
      }
    });
  });
</script>

<?= $this->endSection() ?>