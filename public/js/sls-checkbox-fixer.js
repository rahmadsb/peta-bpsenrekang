/**
 * File: js/sls-checkbox-fixer.js
 * Fungsi utama: Compatibility layer untuk unified checkbox system
 * Note: Unified system sudah menangani semua checkbox management di create.php/edit.php
 */

$(document).ready(function () {
  const DEBUG = true; // Set to false to disable console logs in production

  /**
   * Log debug messages to console
   * @param {string} message - Message to log
   * @param {any} data - Optional data to log
   */
  function logDebug(message, data = null) {
    if (!DEBUG) return;
    if (data) {
      console.log(`[Checkbox Fixer] ${message}`, data);
    } else {
      console.log(`[Checkbox Fixer] ${message}`);
    }
  }

  // Support untuk event legacy - redirect ke unified system
  $(document).on('update-wilkerstat-checkboxes', function (e, data) {
    logDebug('Legacy event update-wilkerstat-checkboxes received, delegating to unified system:', data);

    // Trigger unified system force sync jika tersedia
    if (window.forceSyncCheckboxes && typeof window.forceSyncCheckboxes === 'function') {
      setTimeout(window.forceSyncCheckboxes, 100);
    }
  });

  // Support untuk event import success - redirect ke unified system  
  $(document).on('import-wilkerstat-success', function (e, data) {
    logDebug('Legacy event import-wilkerstat-success received, delegating to unified system:', data);

    // Trigger unified system force sync dan refresh sorting
    if (window.forceSyncCheckboxes && typeof window.forceSyncCheckboxes === 'function') {
      setTimeout(window.forceSyncCheckboxes, 100);
    }

    if (window.refreshTableSorting && typeof window.refreshTableSorting === 'function') {
      setTimeout(window.refreshTableSorting, 200);
    }
  });

  // Legacy selection update events - delegate to unified system
  $(document).on('slsSelectionUpdated', function (event, ids) {
    logDebug('Legacy SLS selection updated event received, delegating to unified system:', ids);
    // Unified system handles this automatically
  });

  $(document).on('bsSelectionUpdated', function (event, ids) {
    logDebug('Legacy BS selection updated event received, delegating to unified system:', ids);
    // Unified system handles this automatically
  });

  $(document).on('desaSelectionUpdated', function (event, ids) {
    logDebug('Legacy Desa selection updated event received, delegating to unified system:', ids);
    // Unified system handles this automatically
  });

  logDebug('Compatibility layer initialized - using unified checkbox system');
});
