</div> <!-- .container -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Reset Form Modal saat tombol Tambah diklik
  const btnTambah = document.querySelector('[data-bs-target="#modalForm"]');
  if (btnTambah) {
    btnTambah.addEventListener('click', function() {
      const form = document.querySelector('#modalForm form');
      if (form) form.reset();
      const formAksi = document.getElementById('formAksi');
      const formId = document.getElementById('formId');
      const modalTitle = document.getElementById('modalFormTitle');
      if (formAksi) formAksi.value = 'tambah';
      if (formId) formId.value = '';
      if (modalTitle) modalTitle.innerText = modalTitle.innerText.replace('Ubah', 'Tambah');
    });
  }

  // Auto Fill Form Modal saat tombol Ubah diklik
  document.querySelectorAll('.btn-edit').forEach(btn => {
    btn.addEventListener('click', function() {
      const data = JSON.parse(this.getAttribute('data-json'));
      const form = document.querySelector('#modalForm form');
      const modalTitle = document.getElementById('modalFormTitle');
      const formAksi = document.getElementById('formAksi');
      const formId = document.getElementById('formId');

      if (modalTitle) modalTitle.innerText = modalTitle.innerText.replace('Tambah', 'Ubah');
      if (formAksi) formAksi.value = 'ubah';

      // Isi nilai input berdasarkan key JSON
      Object.keys(data).forEach(key => {
        if (key === 'id') {
          if (formId) formId.value = data[key];
        }
        const input = form.querySelector(`[name="${key}"]`);
        if (input) {
          input.value = data[key];
        }
      });

      // Buka Modal
      const modalElement = document.getElementById('modalForm');
      if (modalElement) {
        const modalInstance = bootstrap.Modal.getOrCreateInstance(modalElement);
        modalInstance.show();
      }
    });
  });
});
</script>
</body>
</html>