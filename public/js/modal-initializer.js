/**
 * modal-initializer.js
 * Initializes modal triggers for the application
 */

$(document).ready(function () {
  const DEBUG = true;

  /**
   * Log debug messages to console
   * @param {string} message - Message to log
   * @param {any} data - Optional data to log
   */
  function logDebug(message, data = null) {
    if (!DEBUG) return;
    if (data) {
      console.log(`[Modal Initializer] ${message}`, data);
    } else {
      console.log(`[Modal Initializer] ${message}`);
    }
  }

  // Initialize the import wilkerstat modal trigger
  $('.btn-import-wilkerstat').on('click', function () {
    logDebug('Import wilkerstat button clicked');
    $('#importWilkerstatModal').modal('show');
  });

  // Initialize bs-custom-file-input for file input styling
  $(document).ready(function () {
    bsCustomFileInput.init();
  });

  // Handle submit button in the import modal
  $('#btnSubmitImport').on('click', function () {
    logDebug('Submit import button clicked');
    $('#formImportWilkerstat').submit();
  });

  logDebug('Modal initializers loaded successfully');
});
