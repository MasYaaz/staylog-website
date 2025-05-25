<?php
// Koneksi ke database MySQL (ganti sesuai pengaturan XAMPP kamu)
$host = 'localhost';
$user = 'root';
$password = ''; // Default XAMPP tidak pakai password
$dbname = 'staylog-website'; // Sesuaikan dengan nama database kamu

$conn = new mysqli($host, $user, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}
?>