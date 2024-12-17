<?php
$host = 'localhost';
$db = "luxury_rent";
$user = 'root'; // Sesuaikan dengan konfigurasi MySQL Anda
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$sql = "SELECT * FROM cars";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Harga Mobil Rental</title>
    <link rel="stylesheet" href="css/styletabel.css">
</head>
<body>
    <h1>Daftar Harga Mobil Rental</h1>
    <p>Pilihan mobil mewah terbaik untuk kenyamanan perjalanan Anda. Kami menyediakan berbagai mobil premium dengan tarif terjangkau, per hari, atau sewa bulanan.</p>
    
    <table>
        <tr>
            <th>Gambar Mobil</th>
            <th>Nama Mobil</th>
            <th>Harga per Hari</th>
            <th>Sewa Bulanan</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><img src="<?= $row['image_url'] ?>" alt="<?= $row['car_name'] ?>"></td>
            <td><?= $row['car_name'] ?></td>
            <td>Rp<?= number_format($row['price_per_day'], 0, ',', '.') ?> /hari</td>
            <td>Rp<?= number_format($row['monthly_rent'], 0, ',', '.') ?> /bulan</td>
            <td><a href="Form.php" class="button">Book Now</a></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
