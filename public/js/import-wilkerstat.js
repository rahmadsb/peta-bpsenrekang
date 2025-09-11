/**
 * import-wilkerstat.js
 * Handles the import of wilkerstat data from Excel files
 * and updates the appropriate tabs and checkboxes
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
      console.log(`[Import Wilkerstat] ${message}`, data);
    } else {
      console.log(`[Import Wilkerstat] ${message}`);
    }
  }

  // Add custom CSS for selected rows if not already added
  if ($('head style:contains(".selected-row")').length === 0) {
    $('<style>')
      .prop('type', 'text/css')
      .html(`
        .selected-row {
          background-color: rgba(0, 123, 255, 0.1) !important;
        }
        .selected-row:hover {
          background-color: rgba(0, 123, 255, 0.15) !important;
        }
      `)
      .appendTo('head');
  }

  // Create local storage keys for persisting selections
  const STORAGE_KEY_BS = 'peta_bps_selected_bs';
  const STORAGE_KEY_SLS = 'peta_bps_selected_sls';
  const STORAGE_KEY_DESA = 'peta_bps_selected_desa';

  // Make sure we have global arrays for selections
  if (typeof selected_bs === 'undefined') window.selected_bs = [];
  if (typeof selected_sls === 'undefined') window.selected_sls = [];
  if (typeof selected_desa === 'undefined') window.selected_desa = [];

  // Check if we're on the create page or edit page
  const isCreatePage = window.location.href.includes('/kegiatan/create');

  // Clear localStorage for new kegiatan
  if (isCreatePage) {
    try {
      localStorage.removeItem(STORAGE_KEY_BS);
      localStorage.removeItem(STORAGE_KEY_SLS);
      localStorage.removeItem(STORAGE_KEY_DESA);

      // Ensure arrays are empty for new kegiatan
      window.selected_bs = [];
      window.selected_sls = [];
      window.selected_desa = [];

      logDebug('Reset selections for new kegiatan');
    } catch (e) {
      logDebug('Error clearing localStorage', e);
    }
  }
  // Initialize from localStorage if editing existing kegiatan
  else {
    try {
      const storedBs = localStorage.getItem(STORAGE_KEY_BS);
      const storedSls = localStorage.getItem(STORAGE_KEY_SLS);
      const storedDesa = localStorage.getItem(STORAGE_KEY_DESA);

      if (storedBs) {
        window.selected_bs = JSON.parse(storedBs);
        logDebug('Loaded BS selections from localStorage', window.selected_bs.length);
      }
      if (storedSls) {
        window.selected_sls = JSON.parse(storedSls);
        logDebug('Loaded SLS selections from localStorage', window.selected_sls.length);
      }
      if (storedDesa) {
        window.selected_desa = JSON.parse(storedDesa);
        logDebug('Loaded DESA selections from localStorage', window.selected_desa.length);
      }
    } catch (e) {
      logDebug('Error loading from localStorage', e);
    }
  }

  /**
   * Update tab badges to show selection counts
   */
  function updateTabBadges() {
    // Add or update badge on Blok Sensus tab
    if (selected_bs.length > 0 && $('#blok-sensus-tab').length > 0) {
      let badge = $('#blok-sensus-tab .badge');
      if (badge.length === 0) {
        $('#blok-sensus-tab').append(`<span class="badge badge-pill badge-info ml-1">(${selected_bs.length} terpilih)</span>`);
      } else {
        badge.text(`(${selected_bs.length} terpilih)`);
      }
    } else if (selected_bs.length === 0) {
      $('#blok-sensus-tab .badge').remove();
    }

    // Add or update badge on SLS tab
    if (selected_sls.length > 0 && $('#sls-tab').length > 0) {
      let badge = $('#sls-tab .badge');
      if (badge.length === 0) {
        $('#sls-tab').append(`<span class="badge badge-pill badge-info ml-1">(${selected_sls.length} terpilih)</span>`);
      } else {
        badge.text(`(${selected_sls.length} terpilih)`);
      }
    } else if (selected_sls.length === 0) {
      $('#sls-tab .badge').remove();
    }

    // Add or update badge on Desa tab
    if (selected_desa.length > 0 && $('#desa-tab').length > 0) {
      let badge = $('#desa-tab .badge');
      if (badge.length === 0) {
        $('#desa-tab').append(`<span class="badge badge-pill badge-info ml-1">(${selected_desa.length} terpilih)</span>`);
      } else {
        badge.text(`(${selected_desa.length} terpilih)`);
      }
    } else if (selected_desa.length === 0) {
      $('#desa-tab .badge').remove();
    }
  }

  /**
   * Handle form submission
   */
  $('#formImportWilkerstat').on('submit', function (e) {
    e.preventDefault();

    logDebug('Form submitted, starting import...');

    // Show loading state
    $('#btnSubmitImport').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

    // Get form data
    const formData = new FormData(this);

    // Make AJAX request
    $.ajax({
      url: appBaseUrl + 'import-wilkerstat',
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'json',
      success: function (response) {
        logDebug('Server response received', response);

        // Reset button state
        $('#btnSubmitImport').prop('disabled', false).html('<i class="fas fa-file-import"></i> Import');

        // Handle error response
        if (response.status === 'error') {
          let errorMessage = response.message || 'Terjadi kesalahan saat import.';

          // Handle multiple errors
          if (response.errors && response.errors.length > 0) {
            errorMessage = '<ul>';
            response.errors.forEach(function (error) {
              errorMessage += `<li>${error}</li>`;
            });
            errorMessage += '</ul>';
          }

          // Show error message
          Swal.fire({
            title: 'Error!',
            html: errorMessage,
            icon: 'error'
          });
          return;
        }

        // Handle success response
        if (response.status === 'success') {
          logDebug('Import successful, updating UI...');

          // Reset the file input
          $('#importFile').val('');

          // Close the modal
          $('#importWilkerstatModal').modal('hide');

          // Show success message
          Swal.fire({
            title: 'Berhasil!',
            text: response.message,
            icon: 'success'
          });

          // Update selected wilkerstat based on response data
          updateSelectedWilkerstat(response.data);

          // Trigger a custom event that other scripts can listen for
          $(document).trigger('import-wilkerstat-success', [response.data]);
        }
      },
      error: function (xhr, status, error) {
        logDebug('AJAX error', { xhr, status, error });

        // Reset button state
        $('#btnSubmitImport').prop('disabled', false).html('<i class="fas fa-file-import"></i> Import');

        // Show error message
        Swal.fire({
          title: 'Error!',
          text: 'Terjadi kesalahan saat menghubungi server. Silakan coba lagi.',
          icon: 'error'
        });
      }
    });
  });

  /**
   * Update selected wilkerstat based on import response
   * @param {Object} data - Object containing blok_sensus, sls, and desa IDs
   */
  function updateSelectedWilkerstat(data) {
    logDebug('Updating selected wilkerstat', data);

    // Ensure we have a valid data object
    if (!data) {
      logDebug('No data provided to updateSelectedWilkerstat');
      return;
    }

    // Track which tabs have data
    const hasBlokSensusData = data.blok_sensus && data.blok_sensus.length > 0;
    const hasSlsData = data.sls && data.sls.length > 0;
    const hasDesaData = data.desa && data.desa.length > 0;

    logDebug('Data presence check', {
      hasBlokSensusData,
      hasSlsData,
      hasDesaData
    });

    // Keep track of which tab to activate
    let tabToActivate = null;

    // Select the appropriate tab based on data presence (prioritize in this order)
    if (hasBlokSensusData) {
      tabToActivate = 'blok-sensus-tab';
    } else if (hasSlsData) {
      tabToActivate = 'sls-tab';
    } else if (hasDesaData) {
      tabToActivate = 'desa-tab';
    }

    // Only switch tabs if we have data for at least one tab
    if (tabToActivate) {
      logDebug(`Activating tab: ${tabToActivate}`);
      $(`#${tabToActivate}`).tab('show');
    }

    // Handle Blok Sensus checkbox selection
    if (hasBlokSensusData) {
      logDebug('Processing Blok Sensus IDs', data.blok_sensus);
      selectCheckboxes('bs', data.blok_sensus);
    }

    // Handle SLS checkbox selection
    if (hasSlsData) {
      logDebug('Processing SLS IDs', data.sls);
      selectCheckboxes('sls', data.sls);

      // Additional check to ensure SLS checkboxes are properly checked
      setTimeout(() => {
        if (window.forceSyncCheckboxes && typeof window.forceSyncCheckboxes === 'function') {
          window.forceSyncCheckboxes();
        }
      }, 1000);
    }

    // Handle Desa checkbox selection
    if (hasDesaData) {
      logDebug('Processing Desa IDs', data.desa);
      selectCheckboxes('desa', data.desa);
    }

    // Final force sync after all data is processed
    setTimeout(() => {
      if (window.forceSyncCheckboxes && typeof window.forceSyncCheckboxes === 'function') {
        window.forceSyncCheckboxes();
      }
    }, 2000);
  }

  /**
   * Select checkboxes in a DataTable
   * @param {string} type - Type of data (bs, sls, desa)
   * @param {Array} ids - Array of IDs to select
   */
  function selectCheckboxes(type, ids) {
    if (!ids || !ids.length) {
      logDebug(`No IDs provided for ${type}`);
      return;
    }

    logDebug(`Selecting checkboxes for ${type}`, ids);

    // Convert ids to strings to ensure proper comparison
    const stringIds = ids.map(id => id.toString());

    // Update the appropriate global arrays directly
    if (type === 'bs') {
      // Update both arrays for compatibility
      stringIds.forEach(id => {
        if (!selected_bs.includes(id)) {
          selected_bs.push(id);
        }
        if (window.selectedBlokSensus && !window.selectedBlokSensus.includes(id)) {
          window.selectedBlokSensus.push(id);
        }
      });

      logDebug('Updated BS arrays:', { selected_bs: selected_bs.length });
    } else if (type === 'sls') {
      stringIds.forEach(id => {
        if (!selected_sls.includes(id)) {
          selected_sls.push(id);
        }
        if (window.selectedSls && !window.selectedSls.includes(id)) {
          window.selectedSls.push(id);
        }
      });

      logDebug('Updated SLS arrays:', { selected_sls: selected_sls.length });
    } else if (type === 'desa') {
      stringIds.forEach(id => {
        if (!selected_desa.includes(id)) {
          selected_desa.push(id);
        }
        if (window.selectedDesa && !window.selectedDesa.includes(id)) {
          window.selectedDesa.push(id);
        }
      });

      logDebug('Updated DESA arrays:', { selected_desa: selected_desa.length });
    }

    // Use the unified forceSyncCheckboxes function if available
    if (window.forceSyncCheckboxes && typeof window.forceSyncCheckboxes === 'function') {
      setTimeout(() => {
        window.forceSyncCheckboxes();
        logDebug(`Force sync called for ${type}`);
      }, 100);
    } else {
      // Fallback: manual checkbox update
      updateCheckboxesManually(type, stringIds);
    }

    // Trigger a custom event to notify other scripts
    $(document).trigger(`${type}SelectionUpdated`, [stringIds]);
  }

  /**
   * Fallback function to manually update checkboxes if forceSyncCheckboxes is not available
   */
  function updateCheckboxesManually(type, ids) {
    let tableId;
    switch (type) {
      case 'bs':
        tableId = '#table-blok-sensus';
        break;
      case 'sls':
        tableId = '#table-sls';
        break;
      case 'desa':
        tableId = '#table-desa';
        break;
      default:
        return;
    }

    // Update checkboxes in the table
    $(tableId + ' tbody tr').each(function () {
      const checkbox = $(this).find('input[type="checkbox"]');
      const val = checkbox.val();

      if (val && ids.includes(val.toString())) {
        checkbox.prop('checked', true);
        $(this).addClass('selected-row');
      }
    });

    // Update counters
    if (type === 'bs') {
      $('#count-blok-sensus').text(selected_bs.length + ' terpilih');
    } else if (type === 'sls') {
      $('#count-sls').text(selected_sls.length + ' terpilih');
    } else if (type === 'desa') {
      $('#count-desa').text(selected_desa.length + ' terpilih');
    }

    // Update tab badges
    updateTabBadges();
  }

  // When document is fully loaded, update all UI elements
  $(window).on('load', function () {
    // Initialize UI counters
    if ($('#count-blok-sensus').length > 0) {
      $('#count-blok-sensus').text(selected_bs.length + ' terpilih');
    }

    if ($('#count-sls').length > 0) {
      $('#count-sls').text(selected_sls.length + ' terpilih');
    }

    if ($('#count-desa').length > 0) {
      $('#count-desa').text(selected_desa.length + ' terpilih');
    }

    // Update tab badges
    updateTabBadges();

    // Listen for DataTable initialization
    $(document).on('init.dt', function (e, settings) {
      // Get table ID
      const tableId = $(settings.nTable).attr('id');
      logDebug(`DataTable ${tableId} initialized, updating checkboxes`);

      // Update checkboxes after initialization
      setTimeout(function () {
        if (tableId === 'table-blok-sensus') {
          selectCheckboxes('bs', selected_bs);
        } else if (tableId === 'table-sls') {
          selectCheckboxes('sls', selected_sls);
        } else if (tableId === 'table-desa') {
          selectCheckboxes('desa', selected_desa);
        }
      }, 100);
    });
  });
});
