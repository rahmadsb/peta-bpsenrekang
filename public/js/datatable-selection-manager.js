/**
 * datatable-selection-manager.js
 * Manages DataTable selections, ensuring they persist across sorting, searching, and pagination
 */

$(document).ready(function () {
  const DEBUG = true;

  /**
   * Log debug messages to console
   */
  function logDebug(message, data = null) {
    if (!DEBUG) return;
    if (data) {
      console.log(`[Selection Manager] ${message}`, data);
    } else {
      console.log(`[Selection Manager] ${message}`);
    }
  }

  // Initialize selection arrays if they don't exist
  if (typeof selectedBlokSensus === 'undefined') window.selectedBlokSensus = [];
  if (typeof selectedSls === 'undefined') window.selectedSls = [];
  if (typeof selectedDesa === 'undefined') window.selectedDesa = [];

  if (typeof selected_bs === 'undefined') window.selected_bs = [];
  if (typeof selected_sls === 'undefined') window.selected_sls = [];
  if (typeof selected_desa === 'undefined') window.selected_desa = [];

  // Make sure arrays are in sync
  function syncArrays() {
    // Convert all IDs to strings for consistent comparison
    window.selected_bs = [...new Set([...selected_bs, ...selectedBlokSensus].map(id => id.toString()))];
    window.selectedBlokSensus = [...selected_bs];

    window.selected_sls = [...new Set([...selected_sls, ...selectedSls].map(id => id.toString()))];
    window.selectedSls = [...selected_sls];

    window.selected_desa = [...new Set([...selected_desa, ...selectedDesa].map(id => id.toString()))];
    window.selectedDesa = [...selected_desa];

    logDebug('Arrays synchronized', {
      selected_bs: selected_bs.length,
      selected_sls: selected_sls.length,
      selected_desa: selected_desa.length
    });
  }

  // Initial sync
  syncArrays();

  // Update the UI counter badges
  function updateCounters() {
    $('#count-blok-sensus').text(selected_bs.length + ' terpilih');
    $('#count-sls').text(selected_sls.length + ' terpilih');
    $('#count-desa').text(selected_desa.length + ' terpilih');
  }

  // Make sure checkboxes reflect the current selection state
  function updateCheckboxes() {
    logDebug("Updating checkboxes to match selections");

    // Update Blok Sensus checkboxes
    if ($('#table-blok-sensus').length > 0) {
      $('#table-blok-sensus tbody input[type="checkbox"]').each(function () {
        const val = $(this).val().toString();
        $(this).prop('checked', selected_bs.includes(val));
      });
    }

    // Update SLS checkboxes
    if ($('#table-sls').length > 0) {
      $('#table-sls tbody input[type="checkbox"]').each(function () {
        const val = $(this).val().toString();
        const isChecked = selected_sls.includes(val);
        $(this).prop('checked', isChecked);
        logDebug(`SLS ID ${val}: ${isChecked ? "checked" : "unchecked"}`);
      });
    }

    // Update Desa checkboxes
    if ($('#table-desa').length > 0) {
      $('#table-desa tbody input[type="checkbox"]').each(function () {
        const val = $(this).val().toString();
        $(this).prop('checked', selected_desa.includes(val));
      });
    }

    // Backup method using DataTables API
    try {
      // Update Blok Sensus checkboxes via DataTables API
      if ($('#table-blok-sensus').length > 0 && $.fn.DataTable.isDataTable('#table-blok-sensus')) {
        const bsTable = $('#table-blok-sensus').DataTable();
        bsTable.rows().every(function () {
          if (!this.data()) return;
          const rowId = this.data()[0]?.toString();
          if (!rowId) return;
          const checkbox = $(this.node()).find('input[type="checkbox"]');
          if (checkbox.length) {
            checkbox.prop('checked', selected_bs.includes(rowId));
          }
        });
      }

      // Update SLS checkboxes via DataTables API
      if ($('#table-sls').length > 0 && $.fn.DataTable.isDataTable('#table-sls')) {
        const slsTable = $('#table-sls').DataTable();
        slsTable.rows().every(function () {
          if (!this.data()) return;
          const rowId = this.data()[0]?.toString();
          if (!rowId) return;
          const checkbox = $(this.node()).find('input[type="checkbox"]');
          if (checkbox.length) {
            const isChecked = selected_sls.includes(rowId);
            checkbox.prop('checked', isChecked);
            logDebug(`SLS DataTable row ${rowId}: ${isChecked ? "checked" : "unchecked"}`);
          }
        });
      }

      // Update Desa checkboxes via DataTables API
      if ($('#table-desa').length > 0 && $.fn.DataTable.isDataTable('#table-desa')) {
        const desaTable = $('#table-desa').DataTable();
        desaTable.rows().every(function () {
          if (!this.data()) return;
          const rowId = this.data()[0]?.toString();
          if (!rowId) return;
          const checkbox = $(this.node()).find('input[type="checkbox"]');
          if (checkbox.length) {
            checkbox.prop('checked', selected_desa.includes(rowId));
          }
        });
      }
    } catch (e) {
      logDebug('Error updating checkboxes via DataTables API', e);
    }
  }

  // Add custom ordering for checkbox column
  $.fn.dataTable.ext.order['dom-checkbox'] = function (settings, col) {
    return this.api().column(col, { order: 'index' }).nodes().map(function (td, i) {
      return $('input', td).prop('checked') ? '1' : '0';
    });
  };

  // Initialize DataTables with our custom ordering
  function initDataTables() {
    const tableConfig = {
      paging: true,
      searching: true,
      ordering: true,
      info: false,
      lengthMenu: [10, 25, 50, 100],
      columnDefs: [
        {
          orderable: true,
          targets: 0,
          orderDataType: 'dom-checkbox'
        }
      ]
    };

    // Initialize tables if they aren't already
    if (!$.fn.DataTable.isDataTable('#table-blok-sensus') && $('#table-blok-sensus').length > 0) {
      $('#table-blok-sensus').DataTable(tableConfig);
    }

    if (!$.fn.DataTable.isDataTable('#table-sls') && $('#table-sls').length > 0) {
      $('#table-sls').DataTable(tableConfig);
    }

    if (!$.fn.DataTable.isDataTable('#table-desa') && $('#table-desa').length > 0) {
      $('#table-desa').DataTable(tableConfig);
    }
  }

  // Setup event handlers for the DataTable events
  function setupEventHandlers() {
    // Update checkboxes whenever DataTable is redrawn (sorting, filtering, pagination)
    $('.dataTable').on('draw.dt', function () {
      logDebug('DataTable redrawn, updating checkboxes');
      syncArrays();
      updateCheckboxes();
      updateCounters();
    });

    // Handle checkbox changes
    $('#table-blok-sensus').on('change', 'input[type="checkbox"]', function () {
      const val = $(this).val().toString();
      if (this.checked) {
        if (!selected_bs.includes(val)) {
          selected_bs.push(val);
          selectedBlokSensus.push(val);
        }
      } else {
        const idx1 = selected_bs.indexOf(val);
        const idx2 = selectedBlokSensus.indexOf(val);
        if (idx1 !== -1) selected_bs.splice(idx1, 1);
        if (idx2 !== -1) selectedBlokSensus.splice(idx2, 1);
      }
      updateCounters();
    });

    $('#table-sls').on('change', 'input[type="checkbox"]', function () {
      const val = $(this).val().toString();
      if (this.checked) {
        if (!selected_sls.includes(val)) {
          selected_sls.push(val);
          selectedSls.push(val);
        }
      } else {
        const idx1 = selected_sls.indexOf(val);
        const idx2 = selectedSls.indexOf(val);
        if (idx1 !== -1) selected_sls.splice(idx1, 1);
        if (idx2 !== -1) selectedSls.splice(idx2, 1);
      }
      updateCounters();
    });

    $('#table-desa').on('change', 'input[type="checkbox"]', function () {
      const val = $(this).val().toString();
      if (this.checked) {
        if (!selected_desa.includes(val)) {
          selected_desa.push(val);
          selectedDesa.push(val);
        }
      } else {
        const idx1 = selected_desa.indexOf(val);
        const idx2 = selectedDesa.indexOf(val);
        if (idx1 !== -1) selected_desa.splice(idx1, 1);
        if (idx2 !== -1) selectedDesa.splice(idx2, 1);
      }
      updateCounters();
    });

    // Handle the import-wilkerstat-success event
    $(document).on('import-wilkerstat-success', function (e, data) {
      logDebug('Import wilkerstat success event received', data);

      if (data.blok_sensus && data.blok_sensus.length > 0) {
        data.blok_sensus.forEach(id => {
          const strId = id.toString();
          if (!selected_bs.includes(strId)) {
            selected_bs.push(strId);
            selectedBlokSensus.push(strId);
          }
        });
      }

      if (data.sls && data.sls.length > 0) {
        data.sls.forEach(id => {
          const strId = id.toString();
          if (!selected_sls.includes(strId)) {
            selected_sls.push(strId);
            selectedSls.push(strId);
          }
        });
      }

      if (data.desa && data.desa.length > 0) {
        data.desa.forEach(id => {
          const strId = id.toString();
          if (!selected_desa.includes(strId)) {
            selected_desa.push(strId);
            selectedDesa.push(strId);
          }
        });
      }

      syncArrays();
      updateCheckboxes();
      updateCounters();
    });

    // Handle "Select All" buttons
    $('.btn-select-all').on('click', function () {
      const tableId = $(this).data('table');
      const table = $('#' + tableId).DataTable();

      if (tableId === 'table-blok-sensus') {
        // Get all visible checkboxes on current page and add to selections
        table.rows({ search: 'applied', page: 'current' }).nodes().to$().find('input[type="checkbox"]').each(function () {
          const val = $(this).val().toString();
          if (!selected_bs.includes(val)) {
            selected_bs.push(val);
            selectedBlokSensus.push(val);
          }
          $(this).prop('checked', true);
        });
      } else if (tableId === 'table-sls') {
        table.rows({ search: 'applied', page: 'current' }).nodes().to$().find('input[type="checkbox"]').each(function () {
          const val = $(this).val().toString();
          if (!selected_sls.includes(val)) {
            selected_sls.push(val);
            selectedSls.push(val);
          }
          $(this).prop('checked', true);
        });
      } else if (tableId === 'table-desa') {
        table.rows({ search: 'applied', page: 'current' }).nodes().to$().find('input[type="checkbox"]').each(function () {
          const val = $(this).val().toString();
          if (!selected_desa.includes(val)) {
            selected_desa.push(val);
            selectedDesa.push(val);
          }
          $(this).prop('checked', true);
        });
      }

      syncArrays();
      updateCounters();
    });

    // Handle "Uncheck All" buttons
    $('.btn-unselect-all').on('click', function () {
      const tableId = $(this).data('table');
      const table = $('#' + tableId).DataTable();

      if (tableId === 'table-blok-sensus') {
        // Remove all visible checkboxes on current page from selections
        table.rows({ search: 'applied', page: 'current' }).nodes().to$().find('input[type="checkbox"]').each(function () {
          const val = $(this).val().toString();
          const idx1 = selected_bs.indexOf(val);
          const idx2 = selectedBlokSensus.indexOf(val);
          if (idx1 !== -1) selected_bs.splice(idx1, 1);
          if (idx2 !== -1) selectedBlokSensus.splice(idx2, 1);
          $(this).prop('checked', false);
        });
      } else if (tableId === 'table-sls') {
        table.rows({ search: 'applied', page: 'current' }).nodes().to$().find('input[type="checkbox"]').each(function () {
          const val = $(this).val().toString();
          const idx1 = selected_sls.indexOf(val);
          const idx2 = selectedSls.indexOf(val);
          if (idx1 !== -1) selected_sls.splice(idx1, 1);
          if (idx2 !== -1) selectedSls.splice(idx2, 1);
          $(this).prop('checked', false);
        });
      } else if (tableId === 'table-desa') {
        table.rows({ search: 'applied', page: 'current' }).nodes().to$().find('input[type="checkbox"]').each(function () {
          const val = $(this).val().toString();
          const idx1 = selected_desa.indexOf(val);
          const idx2 = selectedDesa.indexOf(val);
          if (idx1 !== -1) selected_desa.splice(idx1, 1);
          if (idx2 !== -1) selectedDesa.splice(idx2, 1);
          $(this).prop('checked', false);
        });
      }

      syncArrays();
      updateCounters();
    });
  }

  // Setup form submission - make sure hidden inputs are properly created
  function setupFormSubmission() {
    $('form').on('submit', function () {
      try {
        // Remove existing hidden inputs
        $('input[name="blok_sensus[]"][type=hidden]').remove();
        $('input[name="sls[]"][type=hidden]').remove();
        $('input[name="desa[]"][type=hidden]').remove();

        // Add hidden inputs for all selected values
        selected_bs.forEach(function (val) {
          $('<input>').attr({
            type: 'hidden',
            name: 'blok_sensus[]',
            value: val
          }).appendTo('form');
        });

        selected_sls.forEach(function (val) {
          $('<input>').attr({
            type: 'hidden',
            name: 'sls[]',
            value: val
          }).appendTo('form');
        });

        selected_desa.forEach(function (val) {
          $('<input>').attr({
            type: 'hidden',
            name: 'desa[]',
            value: val
          }).appendTo('form');
        });

        return true;
      } catch (e) {
        console.error('Error preparing form submission:', e);
        return false;
      }
    });
  }

  // Initialize everything
  function init() {
    logDebug('Initializing DataTable Selection Manager');
    syncArrays();
    initDataTables();
    setupEventHandlers();
    setupFormSubmission();
    updateCheckboxes();
    updateCounters();
  }

  // Run initialization
  init();
});
