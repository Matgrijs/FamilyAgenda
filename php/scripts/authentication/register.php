<?php
session_start();
require_once('../dbConfig.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  $sql = "INSERT INTO FamilyMembers (Name, Email, Password) VALUES ('$name', '$email', '$password')";
  $result = $conn->query($sql);

  if ($result === TRUE) {
    // If the insertion was successful, get the ID of the newly inserted row
    // and put it in the session to use it in other queries.
    $lastInsertedId = $conn->insert_id;

    $_SESSION['FamilyMemberId'] = $lastInsertedId;

    header("Location: ../../../screens/dashboard.html");
    exit();
  } else {
    // If the insertion failed
    echo '<script>alert("Registreren van de gebruiker is fout gegaan, probeer het alsjeblieft opnieuw."); window.location.href = "../../../index.html";</script>';
  }
}
?>
