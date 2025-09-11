/**
 * checkbox-force-sync.js
 * Memastikan checkbox dalam DataTable selalu tersinkronisasi dengan array seleksi
 * dan data di localStorage
 */

// Export function to global scope for manual triggering
function forceSyncCheckboxes() {
  console.log('[Checkbox Force Sync] Manual synchronization triggered');

  // Check if we're on the create page or edit page
  const isCreatePage = window.location.href.includes('/kegiatan/create');

  // Helper function to get IDs from localStorage
  function getIdsFromStorage(key) {
    try {
      // On create page, always return empty array
      if (isCreatePage) {
        return [];
      }

      const stored = localStorage.getItem(key);
      return stored ? JSON.parse(stored) : [];
    } catch (e) {
      console.error('[Checkbox Force Sync] Error reading localStorage:', e);
      return [];
    }
  }

  // Get stored selections
  const storageKeys = {
    'bs': 'peta_bps_selected_bs',
    'sls': 'peta_bps_selected_sls',
    'desa': 'peta_bps_selected_desa'
  };

  const storedSelections = {
    'bs': getIdsFromStorage(storageKeys.bs),
    'sls': getIdsFromStorage(storageKeys.sls),
    'desa': getIdsFromStorage(storageKeys.desa)
  };

  // Update global variables if they exist
  if (typeof window.selected_bs !== 'undefined') window.selected_bs = storedSelections.bs;
  if (typeof window.selected_sls !== 'undefined') window.selected_sls = storedSelections.sls;
  if (typeof window.selected_desa !== 'undefined') window.selected_desa = storedSelections.desa;

  if (typeof window.selectedBlokSensus !== 'undefined') window.selectedBlokSensus = storedSelections.bs;
  if (typeof window.selectedSls !== 'undefined') window.selectedSls = storedSelections.sls;
  if (typeof window.selectedDesa !== 'undefined') window.selectedDesa = storedSelections.desa;

  console.log('[Checkbox Force Sync] Retrieved selections:', {
    bs: storedSelections.bs.length,
    sls: storedSelections.sls.length,
    desa: storedSelections.desa.length
  });

  // Update SLS checkboxes
  if ($.fn.DataTable.isDataTable('#table-sls')) {
    const slsTable = $('#table-sls').DataTable();

    slsTable.rows().every(function () {
      try {
        const rowData = this.data();
        if (!rowData) return;

        const rowId = rowData[0].toString();
        if (!rowId) return;

        const $checkbox = $(this.node()).find('input[type="checkbox"]');
        const shouldBeChecked = storedSelections.sls.includes(rowId);

        // Update checkbox state
        if ($checkbox.prop('checked') !== shouldBeChecked) {
          $checkbox.prop('checked', shouldBeChecked);

          // Apply highlighting
          if (shouldBeChecked) {
            $(this.node()).addClass('selected-row');
          } else {
            $(this.node()).removeClass('selected-row');
          }
        }
      } catch (e) {
        console.error('[Checkbox Force Sync] Error updating SLS row:', e);
      }
    });

    // Force redraw without paging reset
    slsTable.rows().invalidate('data').draw(false);

    // Update counter
    if ($('#count-sls').length) {
      $('#count-sls').text(storedSelections.sls.length + ' terpilih');
    }

    console.log('[Checkbox Force Sync] Updated SLS table with', storedSelections.sls.length, 'selections');
  }

  // Update BS checkboxes
  if ($.fn.DataTable.isDataTable('#table-blok-sensus')) {
    const bsTable = $('#table-blok-sensus').DataTable();

    bsTable.rows().every(function () {
      try {
        const rowData = this.data();
        if (!rowData) return;

        const rowId = rowData[0].toString();
        if (!rowId) return;

        const $checkbox = $(this.node()).find('input[type="checkbox"]');
        const shouldBeChecked = storedSelections.bs.includes(rowId);

        // Update checkbox state
        if ($checkbox.prop('checked') !== shouldBeChecked) {
          $checkbox.prop('checked', shouldBeChecked);

          // Apply highlighting
          if (shouldBeChecked) {
            $(this.node()).addClass('selected-row');
          } else {
            $(this.node()).removeClass('selected-row');
          }
        }
      } catch (e) {
        console.error('[Checkbox Force Sync] Error updating BS row:', e);
      }
    });

    // Force redraw without paging reset
    bsTable.rows().invalidate('data').draw(false);

    // Update counter
    if ($('#count-blok-sensus').length) {
      $('#count-blok-sensus').text(storedSelections.bs.length + ' terpilih');
    }

    console.log('[Checkbox Force Sync] Updated Blok Sensus table with', storedSelections.bs.length, 'selections');
  }

  // Update Desa checkboxes
  if ($.fn.DataTable.isDataTable('#table-desa')) {
    const desaTable = $('#table-desa').DataTable();

    desaTable.rows().every(function () {
      try {
        const rowData = this.data();
        if (!rowData) return;

        const rowId = rowData[0].toString();
        if (!rowId) return;

        const $checkbox = $(this.node()).find('input[type="checkbox"]');
        const shouldBeChecked = storedSelections.desa.includes(rowId);

        // Update checkbox state
        if ($checkbox.prop('checked') !== shouldBeChecked) {
          $checkbox.prop('checked', shouldBeChecked);

          // Apply highlighting
          if (shouldBeChecked) {
            $(this.node()).addClass('selected-row');
          } else {
            $(this.node()).removeClass('selected-row');
          }
        }
      } catch (e) {
        console.error('[Checkbox Force Sync] Error updating Desa row:', e);
      }
    });

    // Force redraw without paging reset
    desaTable.rows().invalidate('data').draw(false);

    // Update counter
    if ($('#count-desa').length) {
      $('#count-desa').text(storedSelections.desa.length + ' terpilih');
    }

    console.log('[Checkbox Force Sync] Updated Desa table with', storedSelections.desa.length, 'selections');
  }

  // Update tab badges
  updateTabBadges(storedSelections);
}

// Make the function available globally
window.forceSyncCheckboxes = forceSyncCheckboxes;

// Update badges on tabs
function updateTabBadges(selections) {
  // Add or update badge on Blok Sensus tab
  if (selections.bs.length > 0 && $('#blok-sensus-tab').length > 0) {
    let badge = $('#blok-sensus-tab .badge');
    if (badge.length === 0) {
      $('#blok-sensus-tab').append(`<span class="badge badge-pill badge-info ml-1">(${selections.bs.length} terpilih)</span>`);
    } else {
      badge.text(`(${selections.bs.length} terpilih)`);
    }
  } else if (selections.bs.length === 0) {
    $('#blok-sensus-tab .badge').remove();
  }

  // Add or update badge on SLS tab
  if (selections.sls.length > 0 && $('#sls-tab').length > 0) {
    let badge = $('#sls-tab .badge');
    if (badge.length === 0) {
      $('#sls-tab').append(`<span class="badge badge-pill badge-info ml-1">(${selections.sls.length} terpilih)</span>`);
    } else {
      badge.text(`(${selections.sls.length} terpilih)`);
    }
  } else if (selections.sls.length === 0) {
    $('#sls-tab .badge').remove();
  }

  // Add or update badge on Desa tab
  if (selections.desa.length > 0 && $('#desa-tab').length > 0) {
    let badge = $('#desa-tab .badge');
    if (badge.length === 0) {
      $('#desa-tab').append(`<span class="badge badge-pill badge-info ml-1">(${selections.desa.length} terpilih)</span>`);
    } else {
      badge.text(`(${selections.desa.length} terpilih)`);
    }
  } else if (selections.desa.length === 0) {
    $('#desa-tab .badge').remove();
  }
}

$(document).ready(function () {
  console.log('[Checkbox Force Sync] Initializing...');

  // Check if we're on the create page
  const isCreatePage = window.location.href.includes('/kegiatan/create');

  // Force syncing of checkboxes with stored selections
  function forceSyncCheckboxes() {
    console.log('[Checkbox Force Sync] Running synchronization');

    // Helper function to get IDs from localStorage
    function getIdsFromStorage(key) {
      try {
        // On create page, always return empty array
        if (isCreatePage) {
          return [];
        }

        const stored = localStorage.getItem(key);
        return stored ? JSON.parse(stored) : [];
      } catch (e) {
        console.error('[Checkbox Force Sync] Error reading localStorage:', e);
        return [];
      }
    }

    // Get stored selections
    const storageKeys = {
      'bs': 'peta_bps_selected_bs',
      'sls': 'peta_bps_selected_sls',
      'desa': 'peta_bps_selected_desa'
    };

    const storedSelections = {
      'bs': getIdsFromStorage(storageKeys.bs),
      'sls': getIdsFromStorage(storageKeys.sls),
      'desa': getIdsFromStorage(storageKeys.desa)
    };

    // Update global variables if they exist
    if (typeof window.selected_bs !== 'undefined') window.selected_bs = storedSelections.bs;
    if (typeof window.selected_sls !== 'undefined') window.selected_sls = storedSelections.sls;
    if (typeof window.selected_desa !== 'undefined') window.selected_desa = storedSelections.desa;

    if (typeof window.selectedBlokSensus !== 'undefined') window.selectedBlokSensus = storedSelections.bs;
    if (typeof window.selectedSls !== 'undefined') window.selectedSls = storedSelections.sls;
    if (typeof window.selectedDesa !== 'undefined') window.selectedDesa = storedSelections.desa;

    console.log('[Checkbox Force Sync] Retrieved selections:', {
      bs: storedSelections.bs.length,
      sls: storedSelections.sls.length,
      desa: storedSelections.desa.length
    });

    // Update SLS checkboxes
    if ($.fn.DataTable.isDataTable('#table-sls')) {
      const slsTable = $('#table-sls').DataTable();

      slsTable.rows().every(function () {
        try {
          const rowData = this.data();
          if (!rowData) return;

          const rowId = rowData[0].toString();
          if (!rowId) return;

          const $checkbox = $(this.node()).find('input[type="checkbox"]');
          const shouldBeChecked = storedSelections.sls.includes(rowId);

          // Update checkbox state
          if ($checkbox.prop('checked') !== shouldBeChecked) {
            $checkbox.prop('checked', shouldBeChecked);

            // Apply highlighting
            if (shouldBeChecked) {
              $(this.node()).addClass('selected-row');
            } else {
              $(this.node()).removeClass('selected-row');
            }
          }
        } catch (e) {
          console.error('[Checkbox Force Sync] Error updating SLS row:', e);
        }
      });

      // Force redraw without paging reset
      slsTable.rows().invalidate('data').draw(false);

      // Update counter
      if ($('#count-sls').length) {
        $('#count-sls').text(storedSelections.sls.length + ' terpilih');
      }

      console.log('[Checkbox Force Sync] Updated SLS table with', storedSelections.sls.length, 'selections');
    }

    // Update BS checkboxes
    if ($.fn.DataTable.isDataTable('#table-blok-sensus')) {
      const bsTable = $('#table-blok-sensus').DataTable();

      bsTable.rows().every(function () {
        try {
          const rowData = this.data();
          if (!rowData) return;

          const rowId = rowData[0].toString();
          if (!rowId) return;

          const $checkbox = $(this.node()).find('input[type="checkbox"]');
          const shouldBeChecked = storedSelections.bs.includes(rowId);

          // Update checkbox state
          if ($checkbox.prop('checked') !== shouldBeChecked) {
            $checkbox.prop('checked', shouldBeChecked);

            // Apply highlighting
            if (shouldBeChecked) {
              $(this.node()).addClass('selected-row');
            } else {
              $(this.node()).removeClass('selected-row');
            }
          }
        } catch (e) {
          console.error('[Checkbox Force Sync] Error updating BS row:', e);
        }
      });

      // Force redraw without paging reset
      bsTable.rows().invalidate('data').draw(false);

      // Update counter
      if ($('#count-blok-sensus').length) {
        $('#count-blok-sensus').text(storedSelections.bs.length + ' terpilih');
      }

      console.log('[Checkbox Force Sync] Updated Blok Sensus table with', storedSelections.bs.length, 'selections');
    }

    // Update Desa checkboxes
    if ($.fn.DataTable.isDataTable('#table-desa')) {
      const desaTable = $('#table-desa').DataTable();

      desaTable.rows().every(function () {
        try {
          const rowData = this.data();
          if (!rowData) return;

          const rowId = rowData[0].toString();
          if (!rowId) return;

          const $checkbox = $(this.node()).find('input[type="checkbox"]');
          const shouldBeChecked = storedSelections.desa.includes(rowId);

          // Update checkbox state
          if ($checkbox.prop('checked') !== shouldBeChecked) {
            $checkbox.prop('checked', shouldBeChecked);

            // Apply highlighting
            if (shouldBeChecked) {
              $(this.node()).addClass('selected-row');
            } else {
              $(this.node()).removeClass('selected-row');
            }
          }
        } catch (e) {
          console.error('[Checkbox Force Sync] Error updating Desa row:', e);
        }
      });

      // Force redraw without paging reset
      desaTable.rows().invalidate('data').draw(false);

      // Update counter
      if ($('#count-desa').length) {
        $('#count-desa').text(storedSelections.desa.length + ' terpilih');
      }

      console.log('[Checkbox Force Sync] Updated Desa table with', storedSelections.desa.length, 'selections');
    }

    // Update tab badges
    updateTabBadges(storedSelections);
  }

  // Update badges on tabs
  function updateTabBadges(selections) {
    // Add or update badge on Blok Sensus tab
    if (selections.bs.length > 0 && $('#blok-sensus-tab').length > 0) {
      let badge = $('#blok-sensus-tab .badge');
      if (badge.length === 0) {
        $('#blok-sensus-tab').append(`<span class="badge badge-pill badge-info ml-1">(${selections.bs.length} terpilih)</span>`);
      } else {
        badge.text(`(${selections.bs.length} terpilih)`);
      }
    } else if (selections.bs.length === 0) {
      $('#blok-sensus-tab .badge').remove();
    }

    // Add or update badge on SLS tab
    if (selections.sls.length > 0 && $('#sls-tab').length > 0) {
      let badge = $('#sls-tab .badge');
      if (badge.length === 0) {
        $('#sls-tab').append(`<span class="badge badge-pill badge-info ml-1">(${selections.sls.length} terpilih)</span>`);
      } else {
        badge.text(`(${selections.sls.length} terpilih)`);
      }
    } else if (selections.sls.length === 0) {
      $('#sls-tab .badge').remove();
    }

    // Add or update badge on Desa tab
    if (selections.desa.length > 0 && $('#desa-tab').length > 0) {
      let badge = $('#desa-tab .badge');
      if (badge.length === 0) {
        $('#desa-tab').append(`<span class="badge badge-pill badge-info ml-1">(${selections.desa.length} terpilih)</span>`);
      } else {
        badge.text(`(${selections.desa.length} terpilih)`);
      }
    } else if (selections.desa.length === 0) {
      $('#desa-tab .badge').remove();
    }
  }

  // Style for selected rows
  if ($('head style:contains(".selected-row")').length === 0) {
    $('<style>')
      .prop('type', 'text/css')
      .html(`
                .selected-row {
                    background-color: rgba(0, 123, 255, 0.15) !important;
                }
                .selected-row:hover {
                    background-color: rgba(0, 123, 255, 0.25) !important;
                }
            `)
      .appendTo('head');
  }

  // Wait for DataTables to be fully initialized
  $(document).on('init.dt', function (e, settings) {
    console.log('[Checkbox Force Sync] DataTable initialized:', $(settings.nTable).attr('id'));
    setTimeout(forceSyncCheckboxes, 300);
  });

  // Run synchronization after page load
  $(window).on('load', function () {
    console.log('[Checkbox Force Sync] Window loaded, running sync');
    setTimeout(forceSyncCheckboxes, 500);
  });

  // Run synchronization after tab changes
  $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    console.log('[Checkbox Force Sync] Tab changed to', $(e.target).attr('href'));
    setTimeout(forceSyncCheckboxes, 200);
  });

  // Also sync when there's a search in DataTables
  $('.dataTable').on('search.dt', function () {
    console.log('[Checkbox Force Sync] DataTable search triggered');
    setTimeout(forceSyncCheckboxes, 300);
  });

  // And when switching pages
  $('.dataTable').on('page.dt', function () {
    console.log('[Checkbox Force Sync] DataTable page change');
    setTimeout(forceSyncCheckboxes, 300);
  });

  // Run initial synchronization
  setTimeout(forceSyncCheckboxes, 1000);

  // Set up interval to periodically check and sync (every 3 seconds)
  setInterval(forceSyncCheckboxes, 3000);
});
