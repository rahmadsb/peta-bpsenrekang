<?php
// Debug script to check user session and database consistency
require_once 'vendor/autoload.php';

// Start the CodeIgniter application
$app = \Config\Services::codeigniter();
$app->initialize();

// Check current session
$session = \Config\Services::session();
echo "=== Current Session Data ===\n";
echo "Session ID: " . $session->session_id . "\n";
echo "User ID: " . $session->get('user_id') . "\n";
echo "Username: " . $session->get('username') . "\n";
echo "Role: " . $session->get('role') . "\n";
echo "Logged In: " . ($session->get('logged_in') ? 'Yes' : 'No') . "\n";

// Check if user exists in database
$userModel = new \App\Models\UserModel();
$userId = $session->get('user_id');

if ($userId) {
  echo "\n=== User Database Check ===\n";
  $user = $userModel->find($userId);
  if ($user) {
    echo "User found in database:\n";
    echo "ID: " . $user['id'] . "\n";
    echo "Username: " . $user['username'] . "\n";
    echo "Role: " . $user['role'] . "\n";
  } else {
    echo "ERROR: User with ID '$userId' NOT FOUND in database!\n";

    // Show all users for debugging
    echo "\n=== All Users in Database ===\n";
    $allUsers = $userModel->findAll();
    foreach ($allUsers as $u) {
      echo "ID: " . $u['id'] . ", Username: " . $u['username'] . ", Role: " . $u['role'] . "\n";
    }
  }
} else {
  echo "\nERROR: No user_id in session!\n";
}

// Check for orphaned kegiatan records
echo "\n=== Kegiatan Records Check ===\n";
$kegiatanModel = new \App\Models\KegiatanModel();
$db = \Config\Database::connect();
$query = $db->query("
    SELECT k.id, k.id_user, u.username 
    FROM kegiatan k 
    LEFT JOIN users u ON k.id_user = u.id 
    WHERE u.id IS NULL
");
$orphanedRecords = $query->getResultArray();

if (empty($orphanedRecords)) {
  echo "No orphaned kegiatan records found.\n";
} else {
  echo "Found " . count($orphanedRecords) . " orphaned kegiatan records:\n";
  foreach ($orphanedRecords as $record) {
    echo "Kegiatan ID: " . $record['id'] . ", Invalid User ID: " . $record['id_user'] . "\n";
  }
}
