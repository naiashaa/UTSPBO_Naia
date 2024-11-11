<tittle><h1> Naia's Grocery "FRESH MART"</h1></tittle>
<h3> UTS PEMROGRAMAN BERORIENTASI OBJEK </h3>
<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "naiadb";  // Nama database yang sudah Anda buat di phpMyAdmin

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Menambahkan data barang
if (isset($_POST['add'])) {
    $nama_barang = $_POST['nama_barang'];
    $stok = $_POST['stok'];
    $harga_beli = $_POST['harga_beli'];
    $harga_jual = $_POST['harga_jual'];
    
    $stmt = $conn->prepare("INSERT INTO tbl_naiastok (nama_barang, stok, harga_beli, harga_jual) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sidd", $nama_barang, $stok, $harga_beli, $harga_jual);
    $stmt->execute();
    header("Location: index.php");
}

// Menghapus data barang
if (isset($_GET['delete'])) {
    $id_barang = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM tbl_naiastok WHERE id_barang=?");
    $stmt->bind_param("i", $id_barang);
    $stmt->execute();
    header("Location: index.php");
}

// Mengambil data barang untuk ditampilkan
$sql = "SELECT * FROM tbl_naiastok";
$barang = $conn->query($sql);

// Mengedit data barang
if (isset($_GET['edit'])) {
    $id_barang = $_GET['edit'];
    $result = $conn->query("SELECT * FROM tbl_naiastok WHERE id_barang=$id_barang");
    $barang_edit = $result->fetch_assoc();
}

if (isset($_POST['edit_data'])) {
    $id_barang = $_POST['id_barang'];
    $nama_barang = $_POST['nama_barang'];
    $stok = $_POST['stok'];
    $harga_beli = $_POST['harga_beli'];
    $harga_jual = $_POST['harga_jual'];
    
    $stmt = $conn->prepare("UPDATE tbl_naiastok SET nama_barang=?, stok=?, harga_beli=?, harga_jual=? WHERE id_barang=?");
    $stmt->bind_param("siddi", $nama_barang, $stok, $harga_beli, $harga_jual, $id_barang);
    $stmt->execute();
    header("Location: index.php");
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabel Stok</title>
    <style>
        body {
            font-family: 'poppins', sans-serif;
            background-color: #bdd299;
            margin: 0;
            padding: 0;

        }

        .container {
            width: 75%;
            margin: 0 auto;
            padding: 20px;
            background-color: #798665;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            text-align: center;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            padding: 10px 20px;
            background-color: #102f15;
            color: #eaf1b1;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #728c5a;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #000000;
        }
        th {
            background-color: #102f15; /* Warna latar belakang header */
            color: #fcf3e3; /* Warna teks header */
        }


        th, td {
            padding: 10px;
            text-align: left;
        }

        td {
            background-color: #fefae0; /* Warna latar belakang sel */
        }
        
        a {
            color: #5d2510;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tabel Stok</h1>

        <!-- Formulir untuk menambah barang -->
        <form method="POST">
            <h2>Tambah Data Barang</h2>
            <input type="text" name="nama_barang" placeholder="Nama Barang" required>
            <input type="number" name="stok" placeholder="Stok" required>
            <input type="number" name="harga_beli" placeholder="Harga Beli" required step="0.01">
            <input type="number" name="harga_jual" placeholder="Harga Jual" required step="0.01">
            <button type="submit" name="add">Tambah Barang</button>
        </form>

        <!-- Tabel untuk menampilkan data barang -->
        <h2>Daftar Barang</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Barang</th>
                    <th>Nama Barang</th>
                    <th>Stok</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $barang->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id_barang']; ?></td>
                    <td><?php echo $row['nama_barang']; ?></td>
                    <td><?php echo $row['stok']; ?></td>
                    <td><?php echo $row['harga_beli']; ?></td>
                    <td><?php echo $row['harga_jual']; ?></td>
                    <td>
                        <a href="?edit=<?php echo $row['id_barang']; ?>">Edit</a> |
                        <a href="?delete=<?php echo $row['id_barang']; ?>" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <?php if (isset($barang_edit)): ?>
        <!-- Formulir untuk mengedit data barang -->
        <form method="POST">
            <h2>Edit Data Barang</h2>
            <input type="hidden" name="id_barang" value="<?php echo $barang_edit['id_barang']; ?>">
            <input type="text" name="nama_barang" value="<?php echo $barang_edit['nama_barang']; ?>" required>
            <input type="number" name="stok" value="<?php echo $barang_edit['stok']; ?>" required>
            <input type="number" name="harga_beli" value="<?php echo $barang_edit['harga_beli']; ?>" required step="0.01">
            <input type="number" name="harga_jual" value="<?php echo $barang_edit['harga_jual']; ?>" required step="0.01">
            <button type="submit" name="edit_data">Simpan Perubahan</button>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
// Menutup koneksi
$conn->close();
?>



