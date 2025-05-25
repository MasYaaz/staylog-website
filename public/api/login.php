<?php
// Include file koneksi database
require_once 'db.php';

// Header untuk mengizinkan akses dari client (CORS)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Ambil input JSON dari frontend
$data = json_decode(file_get_contents("php://input"), true);

// Validasi input
if (!isset($data['email']) || !isset($data['password'])) {
  echo json_encode(["success" => false, "message" => "Email dan password wajib diisi."]);
  exit;
}

$email = $data['email'];
$password = $data['password'];

// Siapkan query untuk cek user berdasarkan email
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);

// Pastikan statement disiapkan dengan benar
if (!$stmt) {
  echo json_encode(["success" => false, "message" => "Query gagal disiapkan."]);
  exit;
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Cek apakah user ditemukan
if ($user = $result->fetch_assoc()) {
  // Verifikasi password yang dimasukkan dengan hash di database
  if (password_verify($password, $user['password'])) {
    echo json_encode([
      "success" => true,
      "message" => "Login berhasil!",
      "user" => [
        "username" => $user['username'],
        "email" => $user['email'],
        "address" => $user['address']
      ]
    ]);
  } else {
    echo json_encode(["success" => false, "message" => "Password salah."]);
  }
} else {
  echo json_encode(["success" => false, "message" => "Email tidak ditemukan."]);
}

$stmt->close();
$conn->close();
?>
