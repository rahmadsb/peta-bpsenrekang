/**
 * debug-excel-import.js
 * Script untuk debugging import Excel template
 */

$(document).ready(function () {

  // Add debug button
  if ($('#debug-excel-btn').length === 0) {
    const debugBtn = `
      <button type="button" id="debug-excel-btn" class="btn btn-warning btn-sm ml-2">
        <i class="fas fa-bug"></i> Debug Excel
      </button>
    `;
    $('.btn-import-wilkerstat').after(debugBtn);
  }

  // Debug button click handler
  $('#debug-excel-btn').on('click', function () {
    debugExcelTemplate();
  });

  function debugExcelTemplate() {
    console.log('=== DEBUG EXCEL TEMPLATE ===');

    // Test with template file
    fetch('template_import_wilkerstat.xlsx')
      .then(response => response.arrayBuffer())
      .then(data => {
        const workbook = XLSX.read(data, { type: 'array' });

        console.log('Sheets found:', Object.keys(workbook.Sheets));

        // Check SLS sheet
        const slsSheet = workbook.Sheets['SLS'];
        if (slsSheet) {
          const slsRows = XLSX.utils.sheet_to_json(slsSheet, { header: 1 });
          console.log('SLS Sheet rows:', slsRows.length);
          console.log('First 10 SLS rows:', slsRows.slice(0, 10));

          // Check data structure
          for (let i = 1; i < Math.min(slsRows.length, 10); i++) {
            const row = slsRows[i];
            if (row && row[0]) {
              console.log(`Row ${i}: "${row[0]}" (type: ${typeof row[0]})`);
            }
          }
        }

        // Show available SLS codes in table
        console.log('Available SLS codes in table:');
        $('#table-sls tbody tr').each(function (index) {
          const code = $(this).find('td:eq(1)').text().trim();
          const id = $(this).find('input[type="checkbox"]').val();
          console.log(`Table row ${index}: code="${code}", id="${id}"`);
        });

      })
      .catch(error => {
        console.error('Error loading template file:', error);
      });
  }

});
