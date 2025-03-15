<?php
$host = "localhost";
$user = "root"; // Default XAMPP username
$pass = ""; // Kosongkan jika default
$db   = "sesi8_tokoOnline";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
