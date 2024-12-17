<?php
session_start();

// Koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "luxury_rent";

$conn = new mysqli($host, $user, $pass, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Cek apakah form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);

    // Validasi input
    if (empty($username) || empty($password) || empty($confirmPassword)) {
        echo "<script>alert('Semua field harus diisi!'); window.history.back();</script>";
        exit();
    }

    if ($password !== $confirmPassword) {
        echo "<script>alert('Password dan Konfirmasi Password tidak sama!'); window.history.back();</script>";
        exit();
    }

    // Simpan data ke database dengan role 'user'
    $role = 'user';
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);

    if ($stmt->execute()) {
        echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location.href = 'Login.php';</script>";
    } else {
        echo "<script>alert('Registrasi gagal. Username mungkin sudah ada.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register Page</title>
  <link rel="stylesheet" href="css/stylelogin.css">
  <style>
      body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-image: url('resource/welcome.jpg');
      background-size: cover; /* Pastikan gambar memenuhi seluruh layar */
      background-position: center; /* Pusatkan gambar */
      background-attachment: fixed; /* Gambar tetap saat di-scroll */
    }

    .login-container {
      max-width: 400px;
      margin: 50px auto;
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    h2 {
      color: #333;
      margin-bottom: 20px;
    }

    label {
      display: block;
      text-align: left;
      margin-bottom: 5px;
      color: #555;
      font-size: 14px;
      font-weight: bold;
    }

    input {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 14px;
      box-sizing: border-box;
    }

    input:focus {
      border-color: #66a1ed;
      outline: none;
      box-shadow: 0px 0px 5px rgba(102, 161, 237, 0.5);
    }

    .login-button {
      width: 100%;
      padding: 10px 20px;
      background-color: #66a1ed;
      border: none;
      color: white;
      font-size: 16px;
      font-weight: bold;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .login-button:hover {
      background-color: #4c89d4;
    }

    .login-admin {
      margin-top: 10px;
      color: #555;
      font-size: 14px;
      font-weight: bold;
    }

    .login-admin a {
      color: #66a1ed;
      text-decoration: none;
    }

    .login-admin a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2><b>Register</b></h2>
    <form id="loginForm" method="POST" action="Daftar.php">
      <label for="username">Username</label>
      <input type="text" id="username" name="username" placeholder="your username" required>
      
      <label for="password">Password</label>
      <input type="password" id="password" name="password" placeholder="password" required>

      <label for="confirmPassword">Konfirmasi Password</label>
      <input type="password" id="confirmPassword" name="confirmPassword" placeholder="confirm password" required>
      
      <button type="submit" class="login-button">Sign Up</button>

      <div class="login-admin">
        Sudah Punya Akun? <a href="Login.php">Login</a>
      </div>
    </form>
  </div>


  <script>
    // Fungsi untuk validasi form
    function validateForm() {
      const fullName = document.getElementById('fullName').value.trim();
      const phoneNumber = document.getElementById('phoneNumber').value.trim();
      const email = document.getElementById('email').value.trim();
      const password = document.getElementById('password').value.trim();
      const confirmPassword = document.getElementById('confirmPassword').value.trim();
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Pola regex untuk validasi email
      const phoneRegex = /^[0-9]{10,13}$/; // Pola regex untuk validasi nomor telepon

      // Validasi input kosong
      if (fullName === '') {
        alert('Nama Lengkap harus diisi!');
        return;
      }

      if (phoneNumber === '') {
        alert('Nomor Telepon harus diisi!');
        return;
      }

      if (!phoneRegex.test(phoneNumber)) {
        alert('Nomor Telepon harus berupa angka dengan panjang 10-13 karakter!');
        return;
      }

      if (email === '') {
        alert('Email harus diisi!');
        return;
      }

      if (!emailRegex.test(email)) {
        alert('Format email tidak valid! Masukkan email yang benar.');
        return;
      }

      if (password === '') {
        alert('Password harus diisi!');
        return;
      }

      if (confirmPassword === '') {
        alert('Konfirmasi Password harus diisi!');
        return;
      }

      // Validasi kesamaan password
      if (password !== confirmPassword) {
        alert('Password dan Konfirmasi Password tidak sama!');
        return;
      }

      // Jika semua validasi lolos
      alert('Registrasi berhasil!');
      window.location.href = 'LandingPage.html';
    }
  </script>
</body>
</html>
