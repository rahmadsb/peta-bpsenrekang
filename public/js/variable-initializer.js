/**
 * variable-initializer.js
 * Initializes global variables needed by various scripts
 */

// Initialize selection arrays if not already defined
if (typeof selected_bs === 'undefined') {
  var selected_bs = [];

  // If selectedBlokSensus exists, copy its values
  if (typeof selectedBlokSensus !== 'undefined') {
    selected_bs = [...selectedBlokSensus];
  }
}

if (typeof selected_sls === 'undefined') {
  var selected_sls = [];

  // If selectedSls exists, copy its values
  if (typeof selectedSls !== 'undefined') {
    selected_sls = [...selectedSls];
  }
}

if (typeof selected_desa === 'undefined') {
  var selected_desa = [];

  // If selectedDesa exists, copy its values
  if (typeof selectedDesa !== 'undefined') {
    selected_desa = [...selectedDesa];
  }
}

console.log('[Variable Initializer] Arrays initialized:', {
  selected_bs,
  selected_sls,
  selected_desa
});
