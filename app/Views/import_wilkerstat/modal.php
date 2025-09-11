<!-- Modal Import Wilkerstat -->
<div class="modal fade" id="importWilkerstatModal" tabindex="-1" role="dialog" aria-labelledby="importWilkerstatModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="importWilkerstatModalLabel">Import Wilkerstat dari Excel</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="alert alert-info">
          <i class="fas fa-info-circle mr-2"></i> Silakan pilih file Excel (.xlsx atau .xls) untuk mengimport data wilkerstat.
          <ul class="mt-2">
            <li>File harus berisi sheet "Blok Sensus", "SLS", dan/atau "DESA"</li>
            <li>Setiap sheet harus memiliki kolom A (Kode) dan B (Nama)</li>
            <li>Pastikan data wilkerstat sudah ada di sistem</li>
          </ul>
          <p class="mt-2"><strong>Tip:</strong> Jika tombol "Import Wilkerstat dari Excel" di luar modal ini digunakan, file akan diproses langsung di browser tanpa perlu diunggah ke server.</p>
        </div>
        <div class="mb-3">
          <a href="<?= base_url('generate-wilkerstat-example') ?>" class="btn btn-success btn-sm" download>
            <i class="fas fa-download mr-1"></i> Download Template
          </a>
        </div>
        <form id="formImportWilkerstat" enctype="multipart/form-data">
          <div class="form-group">
            <label for="importFile">Pilih File Excel</label>
            <div class="input-group">
              <div class="custom-file">
                <input type="file" class="custom-file-input" id="importFile" name="file_import_wilkerstat" accept=".xlsx,.xls" required>
                <label class="custom-file-label" for="importFile">Pilih file...</label>
              </div>
            </div>
            <small class="form-text text-muted">Format: .xlsx atau .xls</small>
          </div>
          <div class="progress mt-3" style="display: none;">
            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="btnSubmitImport">
          <i class="fas fa-upload mr-1"></i> Import
        </button>
      </div>
      <div class="modal-footer" id="importResultFooter" style="display: none; border-top: 0;">
        <div class="alert alert-success w-100 mb-0" id="importSuccessAlert" style="display: none;">
          <i class="fas fa-check-circle mr-2"></i> <span id="importSuccessMessage"></span>
        </div>
        <div class="alert alert-danger w-100 mb-0" id="importErrorAlert" style="display: none;">
          <i class="fas fa-exclamation-circle mr-2"></i> <span id="importErrorMessage"></span>
          <div id="importErrorDetails" class="mt-2" style="max-height: 150px; overflow-y: auto;"></div>
        </div>
      </div>
    </div>
  </div>
</div>