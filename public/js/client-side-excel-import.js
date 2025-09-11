/**
 * client-side-excel-import.js
 * Handles client-side Excel file parsing and selection of wilkerstat items
 * Uses SheetJS (xlsx.js) library to parse Excel files directly in the browser
 * 
 * Improved version that integrates with the existing system
 */

$(document).ready(function () {
  // Debug mode flag
  const DEBUG = true;

  /**
   * Logger function that respects debug flag
   */
  function log(message, data = null) {
    if (!DEBUG) return;
    if (data) {
      console.log(`[Client Excel Import] ${message}`, data);
    } else {
      console.log(`[Client Excel Import] ${message}`);
    }
  }

  /**
   * Show an error message to the user
   */
  function showError(message) {
    Swal.fire({
      title: 'Error!',
      text: message,
      icon: 'error'
    });
  }

  /**
   * Show a success message to the user
   */
  function showSuccess(message, data = null) {
    let html = message;

    // Add details about the data if available
    if (data) {
      html += '<br><br><b>Detail Import:</b><ul>';
      if (data.blok_sensus && data.blok_sensus.length) {
        html += `<li>Blok Sensus: ${data.blok_sensus.length} item terpilih</li>`;
      }
      if (data.sls && data.sls.length) {
        html += `<li>SLS: ${data.sls.length} item terpilih</li>`;
      }
      if (data.desa && data.desa.length) {
        html += `<li>Desa: ${data.desa.length} item terpilih</li>`;
      }
      html += '</ul>';

      // Add troubleshooting info if no SLS found
      if (data.sls && data.sls.length === 0) {
        html += '<br><small><i>⚠️ Jika SLS tidak terpilih, pastikan kode SLS di Excel sesuai dengan kode di database.</i></small>';
      }
    }

    Swal.fire({
      title: 'Berhasil!',
      html: html,
      icon: 'success',
      width: '600px'
    });
  }

  /**
   * Convert Excel column letter to index (e.g., A -> 0, B -> 1, etc.)
   */
  function colLetterToIndex(letter) {
    let index = 0;
    for (let i = 0; i < letter.length; i++) {
      index = index * 26 + letter.charCodeAt(i) - 'A'.charCodeAt(0) + 1;
    }
    return index - 1;
  }

  /**
   * Retrieve text value from cell at given row and column
   */
  function getCellValue(sheet, rowIndex, colLetter) {
    const colIndex = colLetterToIndex(colLetter);
    const cell = sheet[XLSX.utils.encode_cell({ r: rowIndex, c: colIndex })];
    return cell ? cell.v : null;
  }

  /**
   * Debug function to show all available codes in tables
   */
  function debugShowAvailableCodes() {
    if (!DEBUG) return;

    // Show available BS codes using DataTable API
    const bsTable = $('#table-blok-sensus').DataTable();
    const bsCodes = [];
    bsTable.rows().every(function () {
      const rowData = this.data();
      if (rowData && rowData.length >= 2) {
        const code = $(rowData[1]).text ? $(rowData[1]).text().trim() : rowData[1].toString().trim();
        if (code) bsCodes.push(code);
      }
    });
    log(`Available BS codes in table (ALL ${bsCodes.length} rows):`, bsCodes);

    // Show available SLS codes using DataTable API  
    const slsTable = $('#table-sls').DataTable();
    const slsCodes = [];
    slsTable.rows().every(function () {
      const rowData = this.data();
      if (rowData && rowData.length >= 2) {
        const code = $(rowData[1]).text ? $(rowData[1]).text().trim() : rowData[1].toString().trim();
        if (code) slsCodes.push(code);
      }
    });
    log(`Available SLS codes in table (ALL ${slsCodes.length} rows):`, slsCodes);

    // Show available Desa codes using DataTable API
    const desaTable = $('#table-desa').DataTable();
    const desaCodes = [];
    desaTable.rows().every(function () {
      const rowData = this.data();
      if (rowData && rowData.length >= 2) {
        const code = $(rowData[1]).text ? $(rowData[1]).text().trim() : rowData[1].toString().trim();
        if (code) desaCodes.push(code);
      }
    });
    log(`Available Desa codes in table (ALL ${desaCodes.length} rows):`, desaCodes);
  }

  /**
   * Handle Excel file import
   */
  function handleExcelImport(file) {
    if (!file) {
      showError('Tidak ada file yang dipilih');
      return;
    }

    log('Processing Excel file:', file.name);
    log('🔄 Using DataTable API to search ALL rows (including paginated data)');

    // Debug: show available codes before processing
    debugShowAvailableCodes();

    // Check file extension
    const fileNameParts = file.name.split('.');
    const extension = fileNameParts[fileNameParts.length - 1].toLowerCase();

    if (!['xlsx', 'xls'].includes(extension)) {
      showError('File harus berformat Excel (.xlsx atau .xls)');
      return;
    }

    // Show loading indicator
    const loadingAlert = Swal.fire({
      title: 'Memproses File Excel...',
      text: 'Mohon tunggu sebentar',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      }
    });

    // Read the file as array buffer
    const reader = new FileReader();

    reader.onload = function (e) {
      try {
        const data = new Uint8Array(e.target.result);
        const workbook = XLSX.read(data, { type: 'array' });

        // Log all sheet names for debugging
        log('All sheets in workbook:', Object.keys(workbook.Sheets));

        // Process each sheet and collect wilkerstat IDs
        const result = {
          blok_sensus: [],
          sls: [],
          desa: []
        };

        // Debug: show first few rows of each sheet
        Object.keys(workbook.Sheets).forEach(sheetName => {
          const sheet = workbook.Sheets[sheetName];
          const rows = XLSX.utils.sheet_to_json(sheet, { header: 1 });
          log(`Sheet "${sheetName}" preview:`, rows.slice(0, 5));
        });

        // Process Blok Sensus sheet
        const bsSheet = workbook.Sheets['Blok Sensus'];
        if (bsSheet) {
          log('Processing Blok Sensus sheet');

          // Convert to JSON
          const bsRows = XLSX.utils.sheet_to_json(bsSheet, { header: 1 });
          log(`Blok Sensus sheet has ${bsRows.length} rows`);

          // Skip header row and process each row
          for (let i = 1; i < bsRows.length; i++) {
            const row = bsRows[i];

            // Skip empty rows
            if (!row || row.length === 0 || !row[0]) {
              continue;
            }

            const kodeBS = row[0] ? row[0].toString().trim() : null;

            if (kodeBS && kodeBS !== '') {
              log(`Processing BS row ${i}: ${kodeBS}`);
              // Find matching BS in table
              matchAndSelectBS(kodeBS, result.blok_sensus);
            }
          }

          log(`Found ${result.blok_sensus.length} Blok Sensus matches`);
        } else {
          log('Blok Sensus sheet not found in Excel file');
        }

        // Process SLS sheet
        const slsSheet = workbook.Sheets['SLS'];
        if (slsSheet) {
          log('Processing SLS sheet');

          // Convert to JSON
          const slsRows = XLSX.utils.sheet_to_json(slsSheet, { header: 1 });
          log(`SLS sheet has ${slsRows.length} rows`);

          // Skip header row and process each row
          for (let i = 1; i < slsRows.length; i++) {
            const row = slsRows[i];

            // Skip empty rows
            if (!row || row.length === 0 || !row[0]) {
              continue;
            }

            const kodeSLS = row[0] ? row[0].toString().trim() : null;

            if (kodeSLS && kodeSLS !== '') {
              log(`Processing SLS row ${i}: ${kodeSLS}`);
              // Find matching SLS in table
              matchAndSelectSLS(kodeSLS, result.sls);
            }
          }

          log(`Found ${result.sls.length} SLS matches`);
        } else {
          log('SLS sheet not found in Excel file');
        }

        // Process Desa sheet
        let desaSheet = workbook.Sheets['DESA'];
        if (!desaSheet) {
          desaSheet = workbook.Sheets['Desa']; // Try alternate name
        }

        if (desaSheet) {
          log('Processing DESA sheet');

          // Convert to JSON
          const desaRows = XLSX.utils.sheet_to_json(desaSheet, { header: 1 });
          log(`DESA sheet has ${desaRows.length} rows`);

          // Skip header row and process each row
          for (let i = 1; i < desaRows.length; i++) {
            const row = desaRows[i];

            // Skip empty rows
            if (!row || row.length === 0 || !row[0]) {
              continue;
            }

            const kodeDesa = row[0] ? row[0].toString().trim() : null;

            if (kodeDesa && kodeDesa !== '') {
              log(`Processing Desa row ${i}: ${kodeDesa}`);
              // Find matching Desa in table
              matchAndSelectDesa(kodeDesa, result.desa);
            }
          }

          log(`Found ${result.desa.length} Desa matches`);
        } else {
          log('DESA sheet not found in Excel file');
        }

        // Close loading indicator
        loadingAlert.close();

        // Check if any data was found
        if (result.blok_sensus.length === 0 &&
          result.sls.length === 0 &&
          result.desa.length === 0) {
          showError('Tidak ada data wilkerstat yang sesuai ditemukan dalam file Excel. Pastikan format file benar.');
          return;
        }

        // Update the UI
        updateSelectedWilkerstat(result);

        // Show success message
        showSuccess('Import berhasil!', result);

      } catch (error) {
        log('Error parsing Excel:', error);
        loadingAlert.close();
        showError('Terjadi kesalahan saat membaca file Excel. Pastikan format file benar.');
      }
    };

    reader.onerror = function () {
      log('File reading error');
      loadingAlert.close();
      showError('Terjadi kesalahan saat membaca file.');
    };

    reader.readAsArrayBuffer(file);
  }

  /**
   * Find and select Blok Sensus in table based on kode_bs
   */
  function matchAndSelectBS(kodeBS, resultArray) {
    log(`Looking for BS with code: ${kodeBS}`);
    let found = false;

    // Clean the input code
    const cleanKodeBS = kodeBS.toString().trim();

    // Get DataTable instance to access ALL data, not just visible rows
    const table = $('#table-blok-sensus').DataTable();

    // Use DataTable API to search through ALL rows (including paginated ones)
    // Important: Use { search: 'none' } to bypass any active search filters
    table.rows({ search: 'none' }).every(function () {
      const rowData = this.data();
      if (!rowData || rowData.length < 2) return;

      // Extract kode BS from row data (second column, index 1)
      let rowKodeBS = '';
      try {
        if (typeof rowData[1] === 'string') {
          // Safely extract text from HTML
          if (rowData[1].includes('<')) {
            try {
              const tempDiv = document.createElement('div');
              tempDiv.innerHTML = rowData[1];
              rowKodeBS = (tempDiv.textContent || tempDiv.innerText || '').trim();
            } catch (htmlError) {
              // Fallback to jQuery or regex
              try {
                rowKodeBS = $(rowData[1]).text().trim();
              } catch (jqError) {
                rowKodeBS = rowData[1].replace(/<[^>]*>/g, '').trim();
              }
            }
          } else {
            rowKodeBS = rowData[1].trim();
          }
        } else if (rowData[1] && rowData[1].toString) {
          rowKodeBS = rowData[1].toString().trim();
        }
      } catch (e) {
        log(`Error extracting BS code from row:`, e);
        return;
      }

      log(`Comparing Excel BS "${cleanKodeBS}" with table BS "${rowKodeBS}"`);

      if (rowKodeBS === cleanKodeBS) {
        // Extract BS ID from the first column (checkbox value)
        let bsId = null;
        try {
          if (typeof rowData[0] === 'string') {
            const match = rowData[0].match(/value="([^"]+)"/);
            bsId = match ? match[1] : null;
          } else {
            // Fallback to jQuery method
            const $checkbox = $(rowData[0]).find('input[type="checkbox"]');
            bsId = $checkbox.length ? $checkbox.val() : null;
          }
        } catch (e) {
          log(`Error extracting BS ID from row:`, e);
        }

        if (bsId && !resultArray.includes(bsId)) {
          resultArray.push(bsId);
          log(`✓ Matched BS: ${cleanKodeBS} -> ID: ${bsId}`);
          found = true;
        }
      }
    });

    if (!found) {
      log(`✗ No match found for BS: ${cleanKodeBS}`);
    }
  }

  /**
   * Find and select SLS in table based on kode_sls
   */
  function matchAndSelectSLS(kodeSLS, resultArray) {
    log(`Looking for SLS with code: ${kodeSLS}`);
    let found = false;

    // Clean the input code - remove any extra spaces, convert to string
    const cleanKodeSLS = kodeSLS.toString().trim();

    try {
      // Get DataTable instance to access ALL data, not just visible rows
      const table = $('#table-sls').DataTable();
      log(`Searching through ${table.rows().count()} total SLS rows (including paginated)`);

      // Use DataTable API to search through ALL rows (including paginated ones)
      // Important: Use { search: 'none' } to bypass any active search filters
      table.rows({ search: 'none' }).every(function () {
        const rowData = this.data();
        if (!rowData || rowData.length < 2) return;

        // Extract kode SLS from row data (second column, index 1)
        let rowKodeSLS = '';
        try {
          // Handle different data formats
          if (typeof rowData[1] === 'string') {
            // Safely remove HTML tags
            if (rowData[1].includes('<')) {
              try {
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = rowData[1];
                rowKodeSLS = (tempDiv.textContent || tempDiv.innerText || '').trim();
              } catch (htmlError) {
                // Fallback to regex if DOM parsing fails
                rowKodeSLS = rowData[1].replace(/<[^>]*>/g, '').trim();
              }
            } else {
              rowKodeSLS = rowData[1].trim();
            }
          } else if (rowData[1] && rowData[1].toString) {
            rowKodeSLS = rowData[1].toString().trim();
          }
        } catch (e) {
          log(`Error extracting SLS code from row:`, e);
          return;
        }

        if (!rowKodeSLS) return;

        log(`Comparing Excel SLS "${cleanKodeSLS}" with table SLS "${rowKodeSLS}"`);

        // Try exact match first
        if (rowKodeSLS === cleanKodeSLS) {
          // Extract SLS ID from the first column (checkbox value)
          let slsId = null;
          try {
            if (typeof rowData[0] === 'string') {
              const match = rowData[0].match(/value="([^"]+)"/);
              slsId = match ? match[1] : null;
            }
          } catch (e) {
            log(`Error extracting SLS ID from row:`, e);
          }

          if (slsId && !resultArray.includes(slsId)) {
            resultArray.push(slsId);
            log(`✓ Matched SLS (exact): ${cleanKodeSLS} -> ID: ${slsId}`);
            found = true;
          }
        }
      });
    } catch (e) {
      log(`Error using DataTable API, falling back to DOM search:`, e);

      // Fallback: use DOM search but iterate through all pages
      const table = $('#table-sls').DataTable();
      const totalPages = table.page.info().pages;
      const currentPage = table.page.info().page;

      log(`Fallback: Searching through ${totalPages} pages of SLS data`);

      for (let page = 0; page < totalPages; page++) {
        table.page(page).draw(false);

        $('#table-sls tbody tr').each(function () {
          const rowKodeSLS = $(this).find('td:eq(1)').text().trim();

          if (rowKodeSLS === cleanKodeSLS) {
            const slsId = $(this).find('input[type="checkbox"]').val();
            if (slsId && !resultArray.includes(slsId)) {
              resultArray.push(slsId);
              log(`✓ Matched SLS (fallback): ${cleanKodeSLS} -> ID: ${slsId}`);
              found = true;
            }
          }
        });
      }

      // Restore original page
      table.page(currentPage).draw(false);
    }

    if (!found) {
      log(`✗ No match found for SLS: ${cleanKodeSLS}`);
    }
  }

  /**
   * Find and select Desa in table based on kode_desa
   */
  function matchAndSelectDesa(kodeDesa, resultArray) {
    log(`Looking for Desa with code: ${kodeDesa}`);
    let found = false;

    // Clean the input code
    const cleanKodeDesa = kodeDesa.toString().trim();

    // Get DataTable instance to access ALL data, not just visible rows
    const table = $('#table-desa').DataTable();

    // Use DataTable API to search through ALL rows (including paginated ones)
    // Important: Use { search: 'none' } to bypass any active search filters
    table.rows({ search: 'none' }).every(function () {
      const rowData = this.data();
      if (!rowData || rowData.length < 2) return;

      // Extract kode Desa from row data (second column, index 1)
      let rowKodeDesa = '';
      try {
        if (typeof rowData[1] === 'string') {
          // Safely extract text from HTML
          if (rowData[1].includes('<')) {
            try {
              const tempDiv = document.createElement('div');
              tempDiv.innerHTML = rowData[1];
              rowKodeDesa = (tempDiv.textContent || tempDiv.innerText || '').trim();
            } catch (htmlError) {
              // Fallback to jQuery or regex
              try {
                rowKodeDesa = $(rowData[1]).text().trim();
              } catch (jqError) {
                rowKodeDesa = rowData[1].replace(/<[^>]*>/g, '').trim();
              }
            }
          } else {
            rowKodeDesa = rowData[1].trim();
          }
        } else if (rowData[1] && rowData[1].toString) {
          rowKodeDesa = rowData[1].toString().trim();
        }
      } catch (e) {
        log(`Error extracting Desa code from row:`, e);
        return;
      }

      log(`Comparing Excel Desa "${cleanKodeDesa}" with table Desa "${rowKodeDesa}"`);

      if (rowKodeDesa === cleanKodeDesa) {
        // Extract Desa ID from the first column (checkbox value)
        let desaId = null;
        try {
          if (typeof rowData[0] === 'string') {
            const match = rowData[0].match(/value="([^"]+)"/);
            desaId = match ? match[1] : null;
          } else {
            // Fallback to jQuery method
            const $checkbox = $(rowData[0]).find('input[type="checkbox"]');
            desaId = $checkbox.length ? $checkbox.val() : null;
          }
        } catch (e) {
          log(`Error extracting Desa ID from row:`, e);
        }

        if (desaId && !resultArray.includes(desaId)) {
          resultArray.push(desaId);
          log(`✓ Matched Desa: ${cleanKodeDesa} -> ID: ${desaId}`);
          found = true;
        }
      }
    });

    if (!found) {
      log(`✗ No match found for Desa: ${cleanKodeDesa}`);
    }
  }

  /**
   * Update selected wilkerstat in UI
   */
  function updateSelectedWilkerstat(data) {
    log('Updating selected wilkerstat', data);

    // Ensure we have a valid data object
    if (!data) {
      log('No data provided to updateSelectedWilkerstat');
      return;
    }

    // Track which tabs have data
    const hasBlokSensusData = data.blok_sensus && data.blok_sensus.length > 0;
    const hasSlsData = data.sls && data.sls.length > 0;
    const hasDesaData = data.desa && data.desa.length > 0;

    log('Data presence check', {
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
      log(`Activating tab: ${tabToActivate}`);
      $(`#${tabToActivate}`).tab('show');
    }

    // Handle Blok Sensus checkbox selection
    if (hasBlokSensusData) {
      log('Processing Blok Sensus IDs', data.blok_sensus);
      updateSelections('bs', data.blok_sensus);
    }

    // Handle SLS checkbox selection
    if (hasSlsData) {
      log('Processing SLS IDs', data.sls);
      updateSelections('sls', data.sls);
    }

    // Handle Desa checkbox selection
    if (hasDesaData) {
      log('Processing Desa IDs', data.desa);
      updateSelections('desa', data.desa);
    }

    // Force sync if available
    if (window.forceSyncCheckboxes && typeof window.forceSyncCheckboxes === 'function') {
      setTimeout(window.forceSyncCheckboxes, 500);
    }

    // Also refresh table sorting to ensure correct sorting after import
    if (window.refreshTableSorting && typeof window.refreshTableSorting === 'function') {
      setTimeout(window.refreshTableSorting, 700);
    }

    // Force page length to ensure pagination is working correctly
    if (window.forcePageLength && typeof window.forcePageLength === 'function') {
      setTimeout(function () {
        window.forcePageLength(10);
        console.log('Forced page length to 10 after import');
      }, 800);
    }
  }

  /**
   * Update selections for a specific type
   */
  function updateSelections(type, ids) {
    if (!ids || !ids.length) return;

    // Get the appropriate global array
    let selectedArray;
    let globalArray;

    if (type === 'bs') {
      selectedArray = window.selected_bs = window.selected_bs || [];
      globalArray = window.selectedBlokSensus = window.selectedBlokSensus || [];
    } else if (type === 'sls') {
      selectedArray = window.selected_sls = window.selected_sls || [];
      globalArray = window.selectedSls = window.selectedSls || [];
    } else if (type === 'desa') {
      selectedArray = window.selected_desa = window.selected_desa || [];
      globalArray = window.selectedDesa = window.selectedDesa || [];
    } else {
      return;
    }

    // Convert IDs to strings for consistency
    const stringIds = ids.map(id => id.toString());

    // Add new IDs to both arrays
    stringIds.forEach(id => {
      if (!selectedArray.includes(id)) {
        selectedArray.push(id);
      }
      if (!globalArray.includes(id)) {
        globalArray.push(id);
      }
    });

    // Update the UI
    let tableId;
    let counterId;

    if (type === 'bs') {
      tableId = '#table-blok-sensus';
      counterId = '#count-blok-sensus';
    } else if (type === 'sls') {
      tableId = '#table-sls';
      counterId = '#count-sls';
    } else if (type === 'desa') {
      tableId = '#table-desa';
      counterId = '#count-desa';
    }

    // Update counter
    if ($(counterId).length) {
      $(counterId).text(selectedArray.length + ' terpilih');
    }

    // Update checkboxes in the table
    $(tableId + ' tbody tr').each(function () {
      const checkbox = $(this).find('input[type="checkbox"]');
      const val = checkbox.val();

      if (val && selectedArray.includes(val.toString())) {
        checkbox.prop('checked', true);
        $(this).addClass('selected-row');
      }
    });

    // Update tab badges
    updateTabBadges();

    // Trigger custom event for other components
    $(document).trigger(`${type}SelectionUpdated`, [stringIds]);
  }

  /**
   * Update badges on tabs
   */
  function updateTabBadges() {
    const selected_bs = window.selected_bs || [];
    const selected_sls = window.selected_sls || [];
    const selected_desa = window.selected_desa || [];

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

  // Set up the file input for Excel import
  $('#import-excel-input').on('change', function (e) {
    const file = e.target.files[0];
    if (file) {
      handleExcelImport(file);
    }
  });

  // Set up import button click handler
  $('.btn-import-wilkerstat').on('click', function (e) {
    e.preventDefault();

    // Create a file input element if it doesn't exist
    if ($('#import-excel-input').length === 0) {
      $('<input>')
        .attr({
          type: 'file',
          id: 'import-excel-input',
          accept: '.xlsx, .xls',
          style: 'display: none;'
        })
        .appendTo('body')
        .on('change', function (e) {
          const file = e.target.files[0];
          if (file) {
            handleExcelImport(file);
          }
        });
    }

    // Trigger file selection
    $('#import-excel-input').val(null).trigger('click');
  });
});
