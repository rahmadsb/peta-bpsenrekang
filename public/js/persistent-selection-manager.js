/**
 * persistent-selection-manager.js
 * Manages persistent selections across DataTables, page loads, and form submissions
 * With enhanced persistence for sorting, filtering, and import operations
 */

$(document).ready(function () {
  const DEBUG = true;

  /**
   * Log debug messages to console
   */
  function logDebug(message, data = null) {
    if (!DEBUG) return;
    if (data) {
      console.log(`[Persistent Selection] ${message}`, data);
    } else {
      console.log(`[Persistent Selection] ${message}`);
    }
  }

  // -------------------------------------------------------------------------
  // GLOBAL SELECTION ARRAYS
  // -------------------------------------------------------------------------

  // Initialize selection arrays - global scope for cross-script compatibility
  if (typeof window.selectedBlokSensus === 'undefined') window.selectedBlokSensus = [];
  if (typeof window.selectedSls === 'undefined') window.selectedSls = [];
  if (typeof window.selectedDesa === 'undefined') window.selectedDesa = [];

  if (typeof window.selected_bs === 'undefined') window.selected_bs = [];
  if (typeof window.selected_sls === 'undefined') window.selected_sls = [];
  if (typeof window.selected_desa === 'undefined') window.selected_desa = [];

  // Create local storage backup keys
  const STORAGE_KEY_BS = 'peta_bps_selected_bs';
  const STORAGE_KEY_SLS = 'peta_bps_selected_sls';
  const STORAGE_KEY_DESA = 'peta_bps_selected_desa';

  // Check if we're on the create page or edit page
  const isCreatePage = window.location.href.includes('/kegiatan/create');
  const isEditPage = window.location.href.includes('/kegiatan/edit/');

  // Clear localStorage selections when creating a new kegiatan
  if (isCreatePage) {
    try {
      localStorage.removeItem(STORAGE_KEY_BS);
      localStorage.removeItem(STORAGE_KEY_SLS);
      localStorage.removeItem(STORAGE_KEY_DESA);
      logDebug('Cleared localStorage selections for new kegiatan');
    } catch (e) {
      logDebug('Error clearing localStorage', e);
    }
  }

  // Try to restore selections from localStorage if on edit page
  if (isEditPage) {
    try {
      const storedBs = localStorage.getItem(STORAGE_KEY_BS);
      const storedSls = localStorage.getItem(STORAGE_KEY_SLS);
      const storedDesa = localStorage.getItem(STORAGE_KEY_DESA);

      if (storedBs) window.selected_bs = JSON.parse(storedBs);
      if (storedSls) window.selected_sls = JSON.parse(storedSls);
      if (storedDesa) window.selected_desa = JSON.parse(storedDesa);

      logDebug('Restored selections from localStorage', {
        bs: selected_bs.length,
        sls: selected_sls.length,
        desa: selected_desa.length
      });
    } catch (e) {
      logDebug('Error restoring from localStorage', e);
    }
  }

  // Get PHP-provided initial selections if available (from page load)
  let initialBlokSensus = [];
  let initialSls = [];
  let initialDesa = [];

  // Check if PHP has provided initial selections
  if (typeof phpSelectedBlokSensus !== 'undefined') {
    initialBlokSensus = phpSelectedBlokSensus;
  }

  if (typeof phpSelectedSls !== 'undefined') {
    initialSls = phpSelectedSls;
  }

  if (typeof phpSelectedDesa !== 'undefined') {
    initialDesa = phpSelectedDesa;
  }

  logDebug('Initial selections from PHP', {
    blokSensus: initialBlokSensus.length,
    sls: initialSls.length,
    desa: initialDesa.length
  });

  // -------------------------------------------------------------------------
  // SYNCHRONIZATION AND MANAGEMENT FUNCTIONS
  // -------------------------------------------------------------------------

  // Synchronize all selection arrays and store in localStorage
  function syncArrays() {
    // Convert all IDs to strings for consistent comparison
    const bsArray = [...new Set([...selected_bs, ...selectedBlokSensus, ...initialBlokSensus].map(id => id.toString()))];
    const slsArray = [...new Set([...selected_sls, ...selectedSls, ...initialSls].map(id => id.toString()))];
    const desaArray = [...new Set([...selected_desa, ...selectedDesa, ...initialDesa].map(id => id.toString()))];

    // Update all arrays
    window.selected_bs = bsArray;
    window.selectedBlokSensus = bsArray;

    window.selected_sls = slsArray;
    window.selectedSls = slsArray;

    window.selected_desa = desaArray;
    window.selectedDesa = desaArray;

    // Store in localStorage for persistence across page loads
    try {
      localStorage.setItem(STORAGE_KEY_BS, JSON.stringify(bsArray));
      localStorage.setItem(STORAGE_KEY_SLS, JSON.stringify(slsArray));
      localStorage.setItem(STORAGE_KEY_DESA, JSON.stringify(desaArray));
      logDebug('Selections saved to localStorage', {
        bs: bsArray.length,
        sls: slsArray.length,
        desa: desaArray.length
      });
    } catch (e) {
      logDebug('Error storing in localStorage', e);
    }

    // Clean up initial arrays after first sync to avoid duplicates
    initialBlokSensus = [];
    initialSls = [];
    initialDesa = [];

    logDebug('Arrays synchronized', {
      selected_bs: selected_bs.length,
      selected_sls: selected_sls.length,
      selected_desa: selected_desa.length
    });
  }

  // Update the UI counter badges
  function updateCounters() {
    if ($('#count-blok-sensus').length > 0) {
      $('#count-blok-sensus').text(selected_bs.length + ' terpilih');
    }

    if ($('#count-sls').length > 0) {
      $('#count-sls').text(selected_sls.length + ' terpilih');
    }

    if ($('#count-desa').length > 0) {
      $('#count-desa').text(selected_desa.length + ' terpilih');
    }

    // Update counter in any tab badges
    updateTabBadges();

    logDebug('Counters updated', {
      bs: selected_bs.length,
      sls: selected_sls.length,
      desa: selected_desa.length
    });
  }

  // Update badges on tabs
  function updateTabBadges() {
    // Add or update badge on Blok Sensus tab
    if (selected_bs.length > 0 && $('#blok-sensus-tab').length > 0) {
      let badge = $('#blok-sensus-tab .badge');
      if (badge.length === 0) {
        $('#blok-sensus-tab').append(`<span class="badge badge-pill badge-info ml-1">${selected_bs.length}</span>`);
      } else {
        badge.text(selected_bs.length);
      }
    }

    // Add or update badge on SLS tab
    if (selected_sls.length > 0 && $('#sls-tab').length > 0) {
      let badge = $('#sls-tab .badge');
      if (badge.length === 0) {
        $('#sls-tab').append(`<span class="badge badge-pill badge-info ml-1">${selected_sls.length}</span>`);
      } else {
        badge.text(selected_sls.length);
      }
    }

    // Add or update badge on Desa tab
    if (selected_desa.length > 0 && $('#desa-tab').length > 0) {
      let badge = $('#desa-tab .badge');
      if (badge.length === 0) {
        $('#desa-tab').append(`<span class="badge badge-pill badge-info ml-1">${selected_desa.length}</span>`);
      } else {
        badge.text(selected_desa.length);
      }
    }
  }

  // Update checkbox states for direct DOM elements
  function updateDOMCheckboxes() {
    // Blok Sensus checkboxes
    $('#table-blok-sensus tbody input[type="checkbox"]').each(function () {
      const val = $(this).val().toString();
      const shouldBeChecked = selected_bs.includes(val);
      if ($(this).prop('checked') !== shouldBeChecked) {
        $(this).prop('checked', shouldBeChecked);
        // Mark the parent row as selected for styling
        if (shouldBeChecked) {
          $(this).closest('tr').addClass('selected-row');
        } else {
          $(this).closest('tr').removeClass('selected-row');
        }
      }
    });

    // SLS checkboxes
    $('#table-sls tbody input[type="checkbox"]').each(function () {
      const val = $(this).val().toString();
      const shouldBeChecked = selected_sls.includes(val);
      if ($(this).prop('checked') !== shouldBeChecked) {
        $(this).prop('checked', shouldBeChecked);
        // Mark the parent row as selected for styling
        if (shouldBeChecked) {
          $(this).closest('tr').addClass('selected-row');
        } else {
          $(this).closest('tr').removeClass('selected-row');
        }
      }
    });

    // Desa checkboxes
    $('#table-desa tbody input[type="checkbox"]').each(function () {
      const val = $(this).val().toString();
      const shouldBeChecked = selected_desa.includes(val);
      if ($(this).prop('checked') !== shouldBeChecked) {
        $(this).prop('checked', shouldBeChecked);
        // Mark the parent row as selected for styling
        if (shouldBeChecked) {
          $(this).closest('tr').addClass('selected-row');
        } else {
          $(this).closest('tr').removeClass('selected-row');
        }
      }
    });
  }

  // Update checkboxes via DataTables API - more robust approach for DataTables
  function updateDataTableCheckboxes() {
    try {
      // Blok Sensus table
      if ($.fn.DataTable.isDataTable('#table-blok-sensus')) {
        const table = $('#table-blok-sensus').DataTable();
        table.rows().every(function () {
          if (!this.data()) return;
          const rowId = this.data()[0]?.toString();
          if (!rowId) return;

          const checkbox = $(this.node()).find('input[type="checkbox"]');
          if (checkbox.length) {
            const shouldBeChecked = selected_bs.includes(rowId);
            if (checkbox.prop('checked') !== shouldBeChecked) {
              checkbox.prop('checked', shouldBeChecked);
              // Visual feedback on the row
              if (shouldBeChecked) {
                $(this.node()).addClass('selected-row');
              } else {
                $(this.node()).removeClass('selected-row');
              }
            }
          }
        });

        // Force redraw to ensure visual state is correct
        table.rows().invalidate('data').draw(false);
      }

      // SLS table
      if ($.fn.DataTable.isDataTable('#table-sls')) {
        const table = $('#table-sls').DataTable();
        table.rows().every(function () {
          if (!this.data()) return;
          const rowId = this.data()[0]?.toString();
          if (!rowId) return;

          const checkbox = $(this.node()).find('input[type="checkbox"]');
          if (checkbox.length) {
            const shouldBeChecked = selected_sls.includes(rowId);
            if (checkbox.prop('checked') !== shouldBeChecked) {
              checkbox.prop('checked', shouldBeChecked);
              // Visual feedback on the row
              if (shouldBeChecked) {
                $(this.node()).addClass('selected-row');
              } else {
                $(this.node()).removeClass('selected-row');
              }
              logDebug(`SLS ID ${rowId} set to ${shouldBeChecked ? 'checked' : 'unchecked'}`);
            }
          }
        });

        // Force redraw to ensure visual state is correct
        table.rows().invalidate('data').draw(false);
      }

      // Desa table
      if ($.fn.DataTable.isDataTable('#table-desa')) {
        const table = $('#table-desa').DataTable();
        table.rows().every(function () {
          if (!this.data()) return;
          const rowId = this.data()[0]?.toString();
          if (!rowId) return;

          const checkbox = $(this.node()).find('input[type="checkbox"]');
          if (checkbox.length) {
            const shouldBeChecked = selected_desa.includes(rowId);
            if (checkbox.prop('checked') !== shouldBeChecked) {
              checkbox.prop('checked', shouldBeChecked);
              // Visual feedback on the row
              if (shouldBeChecked) {
                $(this.node()).addClass('selected-row');
              } else {
                $(this.node()).removeClass('selected-row');
              }
            }
          }
        });

        // Force redraw to ensure visual state is correct
        table.rows().invalidate('data').draw(false);
      }
    } catch (e) {
      logDebug('Error updating DataTable checkboxes', e);
    }
  }

  // Master update function that ensures all checkboxes match selections
  function updateAllCheckboxes() {
    logDebug('Updating all checkboxes to match selections');
    updateDOMCheckboxes();
    updateDataTableCheckboxes();
    updateCounters();
  }

  // -------------------------------------------------------------------------
  // DATATABLE CONFIGURATION
  // -------------------------------------------------------------------------

  // Add custom ordering for checkbox column
  if ($.fn.dataTable && $.fn.dataTable.ext) {
    $.fn.dataTable.ext.order['dom-checkbox'] = function (settings, col) {
      return this.api().column(col, { order: 'index' }).nodes().map(function (td, i) {
        return $('input', td).prop('checked') ? '1' : '0';
      });
    };
  }

  // Add custom CSS for selected rows
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

  // DataTables configuration with checkbox sorting and persistent selections
  const tableConfig = {
    paging: true,
    searching: true,
    ordering: true,
    info: false,
    lengthMenu: [10, 25, 50, 100],
    columnDefs: [{
      orderable: true,
      targets: 0,
      orderDataType: 'dom-checkbox'
    }],
    // Run after every draw to ensure selections are displayed correctly
    drawCallback: function (settings) {
      logDebug('DataTable drawCallback triggered');
      setTimeout(updateAllCheckboxes, 50);  // Short delay to ensure DOM is ready
    },
    // When table is initialized
    initComplete: function (settings, json) {
      logDebug('DataTable initComplete triggered');
      setTimeout(updateAllCheckboxes, 100); // Short delay to ensure DOM is ready
    },
    // Handle row creation to maintain selection state
    createdRow: function (row, data, dataIndex) {
      const rowId = data[0].toString();

      // Check which table this is and apply appropriate styling
      if ($(this).attr('id') === 'table-blok-sensus' && selected_bs.includes(rowId)) {
        $(row).addClass('selected-row');
        $(row).find('input[type="checkbox"]').prop('checked', true);
      }
      else if ($(this).attr('id') === 'table-sls' && selected_sls.includes(rowId)) {
        $(row).addClass('selected-row');
        $(row).find('input[type="checkbox"]').prop('checked', true);
      }
      else if ($(this).attr('id') === 'table-desa' && selected_desa.includes(rowId)) {
        $(row).addClass('selected-row');
        $(row).find('input[type="checkbox"]').prop('checked', true);
      }
    }
  };

  // Initialize DataTables with our configuration
  function initDataTables() {
    if (!$.fn.DataTable.isDataTable('#table-blok-sensus') && $('#table-blok-sensus').length > 0) {
      $('#table-blok-sensus').DataTable(tableConfig);
    }

    if (!$.fn.DataTable.isDataTable('#table-sls') && $('#table-sls').length > 0) {
      $('#table-sls').DataTable(tableConfig);
    }

    if (!$.fn.DataTable.isDataTable('#table-desa') && $('#table-desa').length > 0) {
      $('#table-desa').DataTable(tableConfig);
    }

    // Apply additional listeners for DataTable events
    $('.dataTable').on('search.dt', function () {
      logDebug('Search event detected, updating checkboxes');
      setTimeout(updateAllCheckboxes, 100);
    });

    $('.dataTable').on('page.dt', function () {
      logDebug('Page change detected, updating checkboxes');
      setTimeout(updateAllCheckboxes, 100);
    });

    $('.dataTable').on('order.dt', function () {
      logDebug('Sorting detected, updating checkboxes');
      setTimeout(updateAllCheckboxes, 100);
    });
  }

  // -------------------------------------------------------------------------
  // EVENT HANDLERS
  // -------------------------------------------------------------------------

  // Handle checkbox changes in Blok Sensus table
  $('#table-blok-sensus').on('change', 'input[type="checkbox"]', function () {
    const val = $(this).val().toString();
    const isChecked = $(this).prop('checked');
    const $row = $(this).closest('tr');

    if (isChecked) {
      // Add to selections
      if (!selected_bs.includes(val)) {
        selected_bs.push(val);
        $row.addClass('selected-row');
      }
    } else {
      // Remove from selections
      const idx = selected_bs.indexOf(val);
      if (idx !== -1) {
        selected_bs.splice(idx, 1);
        $row.removeClass('selected-row');
      }
    }

    syncArrays();
    updateCounters();
  });

  // Handle checkbox changes in SLS table
  $('#table-sls').on('change', 'input[type="checkbox"]', function () {
    const val = $(this).val().toString();
    const isChecked = $(this).prop('checked');
    const $row = $(this).closest('tr');

    if (isChecked) {
      // Add to selections
      if (!selected_sls.includes(val)) {
        selected_sls.push(val);
        $row.addClass('selected-row');
      }
    } else {
      // Remove from selections
      const idx = selected_sls.indexOf(val);
      if (idx !== -1) {
        selected_sls.splice(idx, 1);
        $row.removeClass('selected-row');
      }
    }

    syncArrays();
    updateCounters();
  });

  // Handle checkbox changes in Desa table
  $('#table-desa').on('change', 'input[type="checkbox"]', function () {
    const val = $(this).val().toString();
    const isChecked = $(this).prop('checked');
    const $row = $(this).closest('tr');

    if (isChecked) {
      // Add to selections
      if (!selected_desa.includes(val)) {
        selected_desa.push(val);
        $row.addClass('selected-row');
      }
    } else {
      // Remove from selections
      const idx = selected_desa.indexOf(val);
      if (idx !== -1) {
        selected_desa.splice(idx, 1);
        $row.removeClass('selected-row');
      }
    }

    syncArrays();
    updateCounters();
  });

  // Handle DataTable redraw events (after sort, search, pagination)
  $('.dataTable').on('draw.dt', function () {
    const tableId = $(this).attr('id');
    logDebug(`DataTable ${tableId} redrawn, updating checkboxes`);
    setTimeout(updateAllCheckboxes, 50); // Short delay to ensure DOM is ready
  });

  // Create a MutationObserver to watch for DOM changes in tables
  function setupMutationObservers() {
    // Observer for each table container
    const tableContainers = [
      document.querySelector('#table-blok-sensus_wrapper'),
      document.querySelector('#table-sls_wrapper'),
      document.querySelector('#table-desa_wrapper')
    ];

    const config = { childList: true, subtree: true };

    // Create observer instance
    const observer = new MutationObserver(function (mutations) {
      let needsUpdate = false;

      // Check if any relevant mutations occurred
      mutations.forEach(function (mutation) {
        if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
          for (let node of mutation.addedNodes) {
            if (node.nodeType === 1 &&
              (node.tagName === 'TR' ||
                node.querySelector('input[type="checkbox"]'))) {
              needsUpdate = true;
              break;
            }
          }
        }
      });

      // Update checkboxes if needed
      if (needsUpdate) {
        logDebug('DOM mutation detected, updating checkboxes');
        setTimeout(updateAllCheckboxes, 50);
      }
    });

    // Start observing each table container
    tableContainers.forEach(container => {
      if (container) {
        observer.observe(container, config);
        logDebug('MutationObserver attached to', container.id);
      }
    });
  }

  // Listen for the import-wilkerstat-success event
  $(document).on('import-wilkerstat-success', function (e, data) {
    logDebug('Import wilkerstat success event received', data);

    // Add imported IDs to selections
    if (data.blok_sensus && data.blok_sensus.length > 0) {
      data.blok_sensus.forEach(id => {
        const strId = id.toString();
        if (!selected_bs.includes(strId)) {
          selected_bs.push(strId);
        }
      });
    }

    if (data.sls && data.sls.length > 0) {
      data.sls.forEach(id => {
        const strId = id.toString();
        if (!selected_sls.includes(strId)) {
          selected_sls.push(strId);
        }
      });
    }

    if (data.desa && data.desa.length > 0) {
      data.desa.forEach(id => {
        const strId = id.toString();
        if (!selected_desa.includes(strId)) {
          selected_desa.push(strId);
        }
      });
    }

    syncArrays();
    updateAllCheckboxes();

    // Show success notification with selection counts
    Swal.fire({
      title: 'Import Berhasil!',
      html: `Data telah diimport dengan detail berikut:<br>
                   ${data.blok_sensus?.length > 0 ? '<b>Blok Sensus:</b> ' + data.blok_sensus.length + ' item<br>' : ''}
                   ${data.sls?.length > 0 ? '<b>SLS:</b> ' + data.sls.length + ' item<br>' : ''}
                   ${data.desa?.length > 0 ? '<b>Desa:</b> ' + data.desa.length + ' item' : ''}`,
      icon: 'success'
    });
  });

  // Handle "Select All" buttons - Enhanced version that works with filtering
  $('.btn-select-all').on('click', function () {
    const tableId = $(this).data('table');
    if (!tableId) return;

    // Use DataTables API to get all visible rows (respects filtering)
    if (tableId === 'table-blok-sensus' && $.fn.DataTable.isDataTable('#table-blok-sensus')) {
      const table = $('#table-blok-sensus').DataTable();

      // Get all visible rows
      table.rows({ search: 'applied' }).every(function () {
        const rowId = this.data()[0].toString();
        if (!selected_bs.includes(rowId)) {
          selected_bs.push(rowId);
        }

        // Update UI
        $(this.node()).addClass('selected-row');
        $(this.node()).find('input[type="checkbox"]').prop('checked', true);
      });
    }
    else if (tableId === 'table-sls' && $.fn.DataTable.isDataTable('#table-sls')) {
      const table = $('#table-sls').DataTable();

      table.rows({ search: 'applied' }).every(function () {
        const rowId = this.data()[0].toString();
        if (!selected_sls.includes(rowId)) {
          selected_sls.push(rowId);
        }

        // Update UI
        $(this.node()).addClass('selected-row');
        $(this.node()).find('input[type="checkbox"]').prop('checked', true);
      });
    }
    else if (tableId === 'table-desa' && $.fn.DataTable.isDataTable('#table-desa')) {
      const table = $('#table-desa').DataTable();

      table.rows({ search: 'applied' }).every(function () {
        const rowId = this.data()[0].toString();
        if (!selected_desa.includes(rowId)) {
          selected_desa.push(rowId);
        }

        // Update UI
        $(this.node()).addClass('selected-row');
        $(this.node()).find('input[type="checkbox"]').prop('checked', true);
      });
    }
    // Fallback for when DataTables isn't initialized
    else {
      if (tableId === 'table-blok-sensus') {
        $('#table-blok-sensus tbody input[type="checkbox"]:visible').each(function () {
          const val = $(this).val().toString();
          if (!selected_bs.includes(val)) {
            selected_bs.push(val);
          }
          $(this).prop('checked', true);
          $(this).closest('tr').addClass('selected-row');
        });
      } else if (tableId === 'table-sls') {
        $('#table-sls tbody input[type="checkbox"]:visible').each(function () {
          const val = $(this).val().toString();
          if (!selected_sls.includes(val)) {
            selected_sls.push(val);
          }
          $(this).prop('checked', true);
          $(this).closest('tr').addClass('selected-row');
        });
      } else if (tableId === 'table-desa') {
        $('#table-desa tbody input[type="checkbox"]:visible').each(function () {
          const val = $(this).val().toString();
          if (!selected_desa.includes(val)) {
            selected_desa.push(val);
          }
          $(this).prop('checked', true);
          $(this).closest('tr').addClass('selected-row');
        });
      }
    }

    syncArrays();
    updateCounters();

    // Show notification
    const countMap = {
      'table-blok-sensus': selected_bs.length,
      'table-sls': selected_sls.length,
      'table-desa': selected_desa.length
    };

    const typeLabel = {
      'table-blok-sensus': 'Blok Sensus',
      'table-sls': 'SLS',
      'table-desa': 'Desa'
    };

    Swal.fire({
      title: 'Semua Item Dipilih',
      text: `${countMap[tableId]} ${typeLabel[tableId]} telah dipilih`,
      icon: 'success',
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 2000
    });
  });

  // Handle "Uncheck All" buttons - Enhanced version that works with filtering
  $('.btn-unselect-all').on('click', function () {
    const tableId = $(this).data('table');
    if (!tableId) return;

    // Use DataTables API to get all visible rows (respects filtering)
    if (tableId === 'table-blok-sensus' && $.fn.DataTable.isDataTable('#table-blok-sensus')) {
      const table = $('#table-blok-sensus').DataTable();

      // Get all visible rows
      table.rows({ search: 'applied' }).every(function () {
        const rowId = this.data()[0].toString();
        const idx = selected_bs.indexOf(rowId);
        if (idx !== -1) {
          selected_bs.splice(idx, 1);
        }

        // Update UI
        $(this.node()).removeClass('selected-row');
        $(this.node()).find('input[type="checkbox"]').prop('checked', false);
      });
    }
    else if (tableId === 'table-sls' && $.fn.DataTable.isDataTable('#table-sls')) {
      const table = $('#table-sls').DataTable();

      table.rows({ search: 'applied' }).every(function () {
        const rowId = this.data()[0].toString();
        const idx = selected_sls.indexOf(rowId);
        if (idx !== -1) {
          selected_sls.splice(idx, 1);
        }

        // Update UI
        $(this.node()).removeClass('selected-row');
        $(this.node()).find('input[type="checkbox"]').prop('checked', false);
      });
    }
    else if (tableId === 'table-desa' && $.fn.DataTable.isDataTable('#table-desa')) {
      const table = $('#table-desa').DataTable();

      table.rows({ search: 'applied' }).every(function () {
        const rowId = this.data()[0].toString();
        const idx = selected_desa.indexOf(rowId);
        if (idx !== -1) {
          selected_desa.splice(idx, 1);
        }

        // Update UI
        $(this.node()).removeClass('selected-row');
        $(this.node()).find('input[type="checkbox"]').prop('checked', false);
      });
    }
    // Fallback for when DataTables isn't initialized
    else {
      if (tableId === 'table-blok-sensus') {
        $('#table-blok-sensus tbody input[type="checkbox"]:visible').each(function () {
          const val = $(this).val().toString();
          const idx = selected_bs.indexOf(val);
          if (idx !== -1) {
            selected_bs.splice(idx, 1);
          }
          $(this).prop('checked', false);
          $(this).closest('tr').removeClass('selected-row');
        });
      } else if (tableId === 'table-sls') {
        $('#table-sls tbody input[type="checkbox"]:visible').each(function () {
          const val = $(this).val().toString();
          const idx = selected_sls.indexOf(val);
          if (idx !== -1) {
            selected_sls.splice(idx, 1);
          }
          $(this).prop('checked', false);
          $(this).closest('tr').removeClass('selected-row');
        });
      } else if (tableId === 'table-desa') {
        $('#table-desa tbody input[type="checkbox"]:visible').each(function () {
          const val = $(this).val().toString();
          const idx = selected_desa.indexOf(val);
          if (idx !== -1) {
            selected_desa.splice(idx, 1);
          }
          $(this).prop('checked', false);
          $(this).closest('tr').removeClass('selected-row');
        });
      }
    }

    syncArrays();
    updateCounters();
  });

  // -------------------------------------------------------------------------
  // FORM SUBMISSION HANDLING
  // -------------------------------------------------------------------------

  // Setup form submission handler
  function setupFormSubmission() {
    $('form').on('submit', function (e) {
      try {
        // Force a final sync to ensure all selections are up-to-date
        syncArrays();

        // Remove existing hidden inputs to prevent duplicates
        $('input[name="blok_sensus[]"][type=hidden]').remove();
        $('input[name="sls[]"][type=hidden]').remove();
        $('input[name="desa[]"][type=hidden]').remove();

        logDebug('Form being submitted, adding hidden inputs', {
          blokSensus: selected_bs.length,
          sls: selected_sls.length,
          desa: selected_desa.length
        });

        // Add hidden inputs for all selected values
        selected_bs.forEach(function (val) {
          $('<input>').attr({
            type: 'hidden',
            name: 'blok_sensus[]',
            value: val
          }).appendTo(this);
        }.bind(this));

        selected_sls.forEach(function (val) {
          $('<input>').attr({
            type: 'hidden',
            name: 'sls[]',
            value: val
          }).appendTo(this);
        }.bind(this));

        selected_desa.forEach(function (val) {
          $('<input>').attr({
            type: 'hidden',
            name: 'desa[]',
            value: val
          }).appendTo(this);
        }.bind(this));

        // Log the final form state for debugging
        logDebug('Form ready for submission', {
          hiddenInputs: {
            bs: $('input[name="blok_sensus[]"]').length,
            sls: $('input[name="sls[]"]').length,
            desa: $('input[name="desa[]"]').length
          }
        });

        return true;
      } catch (e) {
        console.error('Error preparing form submission:', e);
        return false;
      }
    });
  }

  // -------------------------------------------------------------------------
  // TAB MANAGEMENT
  // -------------------------------------------------------------------------

  // Handle tab changes to ensure checkboxes are correctly shown
  $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    const targetTab = $(e.target).attr('href');
    logDebug(`Tab changed to ${targetTab}, refreshing checkboxes`);

    // Give the tab time to fully render
    setTimeout(updateAllCheckboxes, 100);
  });

  // -------------------------------------------------------------------------
  // INITIALIZATION
  // -------------------------------------------------------------------------

  // Initialize everything
  function init() {
    logDebug('Initializing Persistent Selection Manager');

    // Only apply selections from PHP data on edit page
    if (isEditPage) {
      syncArrays();
    } else if (isCreatePage) {
      // For create page, ensure arrays are empty and update counters
      window.selected_bs = [];
      window.selected_sls = [];
      window.selected_desa = [];
      window.selectedBlokSensus = [];
      window.selectedSls = [];
      window.selectedDesa = [];
      updateCounters();
    }

    initDataTables();
    setupFormSubmission();
    setupMutationObservers();
    updateAllCheckboxes();

    // Schedule additional updates to ensure checkboxes are correct
    const updateIntervals = [100, 500, 1000, 2000];
    updateIntervals.forEach(interval => {
      setTimeout(updateAllCheckboxes, interval);
    });

    // Listen for import-wilkerstat-success events
    $(document).on('import-wilkerstat-success', function (e, data) {
      logDebug('Import wilkerstat success event received', data);
      setTimeout(updateAllCheckboxes, 300);
    });

    // Listen for selection update events from import-wilkerstat.js
    $(document).on('bsSelectionUpdated slsSelectionUpdated desaSelectionUpdated', function () {
      logDebug('Selection update event received');
      setTimeout(syncArrays, 100);
      setTimeout(updateAllCheckboxes, 300);
    });

    // Add event listeners for tab visibility changes
    if ('visibilityState' in document) {
      document.addEventListener('visibilitychange', function () {
        if (document.visibilityState === 'visible') {
          logDebug('Document became visible, updating checkboxes');
          updateAllCheckboxes();
        }
      });
    }

    // Re-apply styles to ensure they take effect
    $('<style>')
      .prop('type', 'text/css')
      .html(`
                .selected-row {
                    background-color: rgba(0, 123, 255, 0.1) !important;
                }
                .selected-row:hover {
                    background-color: rgba(0, 123, 255, 0.15) !important;
                }
                .tab-pane {
                    padding-top: 15px;
                }
            `)
      .appendTo('head');
  }

  // Run initialization
  init();
});
