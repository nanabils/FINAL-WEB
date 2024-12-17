<?php
session_start();

// Koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "luxury_rent";

$conn = new mysqli($host, $user, $pass, $dbname);

// Cek koneksi database
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Pastikan pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: Login.php");
    exit();
}

// Ambil username dari sesi
$username = $_SESSION['username'];

// Proses input data dari form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pickupLocation = trim($_POST['pickup_location']);
    $dropoffLocation = trim($_POST['dropoff_location']);
    $pickupDate = $_POST['pickup_date'];
    $dropoffDate = $_POST['dropoff_date'];
    $pickupTime = $_POST['pickup_time'];

    // Validasi input
    if (empty($pickupLocation) || empty($dropoffLocation) || empty($pickupDate) || empty($dropoffDate) || empty($pickupTime)) {
        echo "<script>alert('Semua field harus diisi!'); window.history.back();</script>";
        exit();
    }

    // Masukkan data ke database
    $stmt = $conn->prepare("INSERT INTO car_rentals (username, pickup_location, dropoff_location, pickup_date, dropoff_date, pickup_time) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $username, $pickupLocation, $dropoffLocation, $pickupDate, $dropoffDate, $pickupTime);

    if ($stmt->execute()) {
        echo "<script>alert('Pemesanan berhasil!'); window.location.href = 'LandingPage.html';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat memproses pemesanan!'); window.history.back();</script>";
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
  <title>Form Booking</title>
  <link rel="stylesheet" href="css/styleform.css">
  <style>
    /* Animasi gambar pada fitur */
    .feature img {
      width: 110px;
      height: 110px;
      transition: transform 0.3s ease, opacity 0.3s ease;
      opacity: 0;
      transform: translateY(20px); /* Menambahkan efek pergerakan ke atas */
    }

    .feature img.show {
      opacity: 1;
      transform: translateY(0); /* Mengembalikan gambar ke posisi semula */
    }

    .feature {
      text-align: center;
      padding: 20px;
      transition: transform 0.3s ease;
    }

    .feature:hover {
      transform: scale(1.1); /* Menambahkan efek pembesaran saat hover */
    }

    </style>
</head>
<body>
  <div class="container">
    <!-- Form Section -->
    <div class="booking-form">
      <h2>Make your trip</h2>
      <form action="Form.php" method="post">
      <label for="pickup-location">PICK-UP LOCATION</label>
      <input type="text" id="pickup-location" name="pickup_location" placeholder="City, Airport, Station, etc" required>

        <label for="dropoff-location">DROP-OFF LOCATION</label>
        <input type="text" id="dropoff-location" name="dropoff_location" placeholder="City, Airport, Station, etc" required>

        <div class="date-time">
          <div class="date">
            <label for="pickup-date">PICK-UP DATE</label>
            <input type="date" id="pickup-date" name="pickup_date" required>
          </div>
          <div class="date">
            <label for="dropoff-date">DROP-OFF DATE</label>
            <input type="date" id="dropoff-date" name="dropoff_date" required>
          </div>
        </div>

        <label for="pickup-time">PICK-UP TIME</label>
        <input type="time" id="pickup-time" name="pickup_time" required>

        <button type="submit" class="book-now"><b>Rent A Car Now</b></button>
      </form>
    </div>

    <!-- Text Section -->
    <div class="promo-text">
      <h2>Better Way to Rent Your <br> Perfect Cars</h2>
      <div class="features">
        <div class="feature">
          <img src="resource/maps.png" alt="Pickup Icon">
          <p>Choose Your Pickup Location</p>
        </div>
        <div class="feature">
          <img src="resource/hand.png" alt="Deal Icon">
          <p>Select the Best Deal</p>
        </div>
        <div class="feature">
          <img src="resource/car.png" alt="Car Icon">
          <p>Reserve Your Rental Car</p>
        </div>

  </div>
  <script>

  // Fungsi untuk memeriksa elemen yang terlihat di layar
  function checkVisibility() {
    const features = document.querySelectorAll('.feature img');
    features.forEach((feature) => {
      const rect = feature.getBoundingClientRect();
      if (rect.top < window.innerHeight && rect.bottom >= 0) {
        feature.classList.add('show');
      }
    });
  }

  // Panggil fungsi untuk memeriksa visibility saat halaman dimuat
  window.addEventListener('DOMContentLoaded', checkVisibility);

  // Panggil fungsi saat user scroll halaman
  window.addEventListener('scroll', checkVisibility);

    </script>
</body>
</html>
