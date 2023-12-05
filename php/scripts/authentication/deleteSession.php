<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Delete the session
session_destroy();

// Return a response indicating the session has been destroyed
header('Content-Type: application/json');
echo json_encode(['message' => 'Session destroyed']);
?>
