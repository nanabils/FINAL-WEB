<?php
session_start();

// Jalankan hanya jika form dikirim (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Konfigurasi database
  $host = "localhost";
  $user = "root";
  $pass = "";
  $dbname = "luxury_rent";

  // Koneksi ke database
  $conn = new mysqli($host, $user, $pass, $dbname);

  // Cek koneksi
  if ($conn->connect_error) {
      die("Koneksi gagal: " . $conn->connect_error);
  }

  // Ambil data input dari form
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';
  $remember = isset($_POST['remember']);

  // Validasi input kosong
  if (empty($username) || empty($password)) {
      echo "<script>alert('Username atau password tidak boleh kosong!'); window.history.back();</script>";
      exit();
  }

  // Query untuk mencari user berdasarkan username dan password
  $stmt = $conn->prepare("SELECT role FROM users WHERE username = ? AND password = ?");
  $stmt->bind_param("ss", $username, $password);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $role = $row['role'];

      // Set session untuk user
      $_SESSION['username'] = $username;
      $_SESSION['role'] = $role;

      // Redirect berdasarkan role
      if ($role === 'admin') {
          header("Location: LandingPageAdmin.html");
          exit();
      } elseif ($role === 'user') {
          header("Location: LandingPage.html");
          exit();
      }
  } else {
      echo "<script>alert('Username atau password salah!'); window.history.back();</script>";
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
  <title>Login Page</title>
  <link rel="stylesheet" href="css/stylelogin.css">
  <style>
    /* Pastikan gambar background tampil dengan benar */
.background-image {
  position: fixed; /* Fix gambar agar selalu di belakang */
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-image: url('https://www.hdcarwallpapers.com/walls/lamborghini_aventador_s_roadster_4k_2018-HD.jpg');
  background-size: cover; /* Agar gambar memenuhi seluruh layar */
  background-position: center; /* Gambar akan terpusat */
  filter: brightness(0.7); /* Efek gelap pada background agar konten lebih jelas */
  z-index: -1; /* Gambar tetap di bawah konten lainnya */
}
    input {
      padding: 0.8em;
      margin-bottom: 1em;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-size: 1em;
      width: 100%;
    }

    input:hover, input:focus {
      border-color: #66a1ed;
      outline: none;
    }

    .login-button {
      background-color: #66a1ed;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
    }

    .login-button:hover {
      background-color: #508ecc;
    }

    .login-admin {
      margin-top: 10px;
      font-size: 14px;
      color: #555;
    }

    .login-admin a {
      color: #66a1ed;
      text-decoration: none;
      font-weight: bold;
    }

    .login-admin a:hover {
      text-decoration: underline;
    }
    .checkbox-container {
      align-items: left; /* Vertikal center */
      margin-bottom: 0em; /* Jarak bawah */
    }

    .checkbox-container label {
      margin-left: 0.5em; /* Jarak antara checkbox dan teks */
    }
  </style>
</head>
<body>
  <div class="background-image"></div>
  <div class="login-container">
    <h2><b>Login to LuxuryRent</b></h2>
    <form method="POST" action="login.php">
      <!-- Input Username -->
      <label for="username">Username</label>
      <input type="text" id="username" name="username" placeholder="your username" required>

      <!-- Input Password -->
      <label for="password">Password</label>
      <input type="password" id="password" name="password" placeholder="your password" required>
      
      <!-- Tombol Login -->
      <button type="submit" class="login-button">Log In</button>
      
      <!-- Tautan ke halaman Daftar -->
      <br>
      <div class="login-admin">
        Tidak Punya Akun? <a href="Daftar.php">Daftar</a>
      </div>
    </form>
  </div>
</body>
</html>
