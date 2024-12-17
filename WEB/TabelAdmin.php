<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Harga Mobil Rental</title>
    <style>
        .button {
            display: inline-block;
            padding: 8px 12px;
            text-decoration: none;
            font-size: 14px;
            border-radius: 4px;
            margin: 0 5px;
        }

        .button.edit-btn {
            background-color: #4caf50;
            color: white;
        }

        .button.delete-btn {
            background-color: #f44336;
            color: white;
        }

        .button:hover {
            opacity: 0.8;
        }

        form {
            background-color: #fff;
            padding: 2rem;
            margin: 1rem 0;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input[type="text"], input[type="file"], input[type="hidden"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    /* Style for the table */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
    word-wrap: break-word; /* Ensure long words break for better readability */
}

th {
    background-color: #f4f4f4;
    font-weight: bold;
}

/* Set a larger width for the image column */
td img {
    width: 80%;  /* Make image take up 80% of its column width */
    height: auto;  /* Maintain aspect ratio */
    border-radius: 4px;
    display: block;
    margin: 0 auto; /* Center the image */
}

/* Define fixed width for image column */
td:first-child {
    width: 250px; /* Increase the width of the image column */
}

/* Styling for the actions column (decrease its width) */
td:last-child {
    width: 150px; /* Decrease width of actions column */
}

/* Styling for action buttons */
button.edit-btn, a.delete-btn {
    padding: 8px 12px;
    text-decoration: none;
    font-size: 14px;
    border-radius: 4px;
    cursor: pointer;
    display: inline-block;
    margin-top: 10px;
}

button.edit-btn {
    background-color: #4caf50;
    color: white;
    border: none;
}

a.delete-btn {
    background-color: #f44336;
    color: white;
    text-align: center;
    display: inline-block;
}

button.edit-btn:hover, a.delete-btn:hover {
    opacity: 0.8;
}

/* Add some spacing around the table */
table, th, td {
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

/* Make the table responsive */
@media screen and (max-width: 768px) {
    table {
        width: 100%;
        border: none;
    }

    th, td {
        display: block;
        padding: 10px;
        width: 100%;
    }

    th {
        background-color: #f4f4f4;
    }

    td {
        border: none;
        border-bottom: 1px solid #ddd;
    }

    td img {
        width: 90%;  /* Make image 90% of the column width on mobile */
        max-width: 100%;
        height: auto;
    }
}



    </style>
</head>
<body>
<?php
$host = 'localhost';
$db = "luxury_rent";
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$sql = "SELECT * FROM cars";
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit-btn'])) {
    $car_name = $_POST['car_name'];
    $price_per_day = $_POST['price_per_day'];
    $monthly_rent = $_POST['monthly_rent'];
    
    // Proses upload gambar
    if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] == 0) {
        $image_name = $_FILES['image_url']['name'];
        $image_tmp = $_FILES['image_url']['tmp_name'];
        $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
    
        // Tentukan direktori tujuan untuk upload gambar
        $upload_dir = 'uploads/';
        $image_url = $upload_dir . uniqid() . '.' . $image_ext;
    
        // Validasi ekstensi gambar
        if (in_array($image_ext, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($image_tmp, $image_url)) {
                // Berhasil upload, simpan URL gambar ke database
            } else {
                echo "<script>alert('Gagal mengupload gambar.'); window.history.back();</script>";
                exit;
            }
        } else {
            echo "<script>alert('Hanya file gambar yang diperbolehkan (jpg, jpeg, png, gif).'); window.history.back();</script>";
            exit;
        }
    } else {
        // Jika tidak ada file gambar yang dipilih, gunakan URL gambar lama yang ada di database
        $image_url = $_POST['image_url']; // Gambar lama
    }
    
    if (isset($_POST['car_id']) && !empty($_POST['car_id'])) {
        // Edit data
        $car_id = $_POST['car_id'];
        $update_sql = "UPDATE cars SET car_name = ?, price_per_day = ?, monthly_rent = ?, image_url = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssssi", $car_name, $price_per_day, $monthly_rent, $image_url, $car_id);
    } else {
        // Tambah data
        $insert_sql = "INSERT INTO cars (car_name, price_per_day, monthly_rent, image_url) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("ssss", $car_name, $price_per_day, $monthly_rent, $image_url);
    }
    
    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil disimpan!'); window.location.href = 'TabelAdmin.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan data.'); window.history.back();</script>";
    }
    
    $stmt->close();
}

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    $delete_sql = "DELETE FROM cars WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil dihapus!'); window.location.href = 'TabelAdmin.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data.'); window.history.back();</script>";
        
    }

    $stmt->close();
}
?>

    <form id="car-form" method="POST" action="TabelAdmin.php">
    <h2>Tambah/Edit Data Mobil</h2>
    <input type="hidden" id="car_id" name="car_id">
    <label for="car_name">Nama Mobil:</label>
    <input type="text" id="car_name" name="car_name" placeholder="Masukkan nama mobil...">
    <label for="price_per_day">Harga Per Hari:</label>
    <input type="text" id="price_per_day" name="price_per_day" placeholder="Masukkan harga per hari...">
    <label for="monthly_rent">Harga Per Bulan:</label>
    <input type="text" id="monthly_rent" name="monthly_rent" placeholder="Masukkan harga per bulan...">
    <label for="image_url">Gambar Mobil:</label>
    <input type="text" id="image_url" name="image_url" placeholder="Masukkan URL gambar mobil...">
    <button type="submit" name="submit-btn" id="submit-btn">Tambah</button>
</form>


    <h1>Daftar Harga Mobil Rental</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Gambar Mobil</th>
                <th>Nama Mobil</th>
                <th>Harga per Hari</th>
                <th>Harga per Bulan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><img src="<?= htmlspecialchars($row['image_url']) ?>" alt="<?= htmlspecialchars($row['car_name']) ?>" width="100"></td>
                <td><?= htmlspecialchars($row['car_name']) ?></td>
                <td>Rp<?= number_format($row['price_per_day'], 0, ',', '.') ?></td>
                <td>Rp<?= number_format($row['monthly_rent'], 0, ',', '.') ?></td>
                <td>
                    <button class="edit-btn" onclick='editRow(<?= json_encode($row) ?>)'>Edit</button>
                    <a href="TabelAdmin.php?delete_id=<?= $row['id'] ?>" class="button delete-btn" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <script>
function editRow(row) {
    document.getElementById('car_id').value = row.id;
    document.getElementById('car_name').value = row.car_name;
    document.getElementById('price_per_day').value = row.price_per_day;
    document.getElementById('monthly_rent').value = row.monthly_rent;
    document.getElementById('image_url').value = row.image_url;

    document.getElementById('submit-btn').textContent = 'Update';  // Ganti tombol menjadi 'Update' saat editing
}

    </script>
</body>
</html>
