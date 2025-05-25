<?php
require_once 'db.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Ambil data JSON dari frontend
$data = json_decode(file_get_contents("php://input"), true);

// Cek jika data tidak lengkap
if (
  !isset($data['username'], $data['email'], $data['password'], $data['confirmPassword']) ||
  empty(trim($data['username'])) ||
  empty(trim($data['email'])) ||
  empty(trim($data['password'])) ||
  empty(trim($data['confirmPassword']))
) {
  echo json_encode(["success" => false, "message" => "Semua kolom harus diisi."]);
  exit;
}

// Ambil dan sanitasi input
$username = trim($data['username']);
$email = trim($data['email']);
$password = $data['password'];
$confirmPassword = $data['confirmPassword'];

// Validasi format email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  echo json_encode(["success" => false, "message" => "Format email tidak valid."]);
  exit;
}

// Validasi konfirmasi password
if ($password !== $confirmPassword) {
  echo json_encode(["success" => false, "message" => "Password dan konfirmasi tidak cocok."]);
  exit;
}

// Cek apakah email sudah terdaftar
$checkSql = "SELECT id FROM users WHERE email = ?";
$checkStmt = $conn->prepare($checkSql);
$checkStmt->bind_param("s", $email);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
  echo json_encode(["success" => false, "message" => "Email sudah terdaftar."]);
  $checkStmt->close();
  $conn->close();
  exit;
}
$checkStmt->close();

// Hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert ke database
$insertSql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
$insertStmt = $conn->prepare($insertSql);
$insertStmt->bind_param("sss", $username, $email, $hashedPassword);

if ($insertStmt->execute()) {
  echo json_encode(["success" => true, "message" => "Registrasi berhasil!", "user" => ["username" => $username,"email" => $email,]]);
} else {
  echo json_encode(["success" => false, "message" => "Gagal menyimpan data."]);
}

$insertStmt->close();
$conn->close();
?>
