<?php
session_start();

// Check if FamilyMemberId is set in the session
$familyMemberId = isset($_SESSION['FamilyMemberId']) ? $_SESSION['FamilyMemberId'] : null;

// Return the FamilyMemberId as a JSON response
header('Content-Type: application/json');
echo json_encode(['FamilyMemberId' => $familyMemberId]);
?>
