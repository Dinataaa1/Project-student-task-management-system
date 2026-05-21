<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once '../config/koneksi.php';

if (isset($_SESSION['user_id'])) {
    if (isset($_SESSION['role']) && $_SESSION['role'] == 'dosen') {
        header("Location: dashboard/dosen/dashboard.php");
    } else {
        header("Location: dashboard/mahasiswa/dashboard.php");
    }
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = isset($_POST['nama']) ? trim($_POST['nama']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $role = isset($_POST['role']) ? $_POST['role'] : '';

    if ($nama === '' || $email === '' || $password === '' || $role === '') {
        $error = "Semua field harus diisi!";
    } else {
        // mengecek email sudah terdaftar apa belum?
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        if ($check) {
            $check->bind_param("s", $email);
            $check->execute();
            $check->store_result();

            if ($check->num_rows > 0) {
                $error = "Email sudah terdaftar! Gunakan email lain.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $insert = $conn->prepare("INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, ?)");
                if ($insert) {
                    $insert->bind_param("ssss", $nama, $email, $hashed_password, $role);
                    if ($insert->execute()) {
                        $success = "Registrasi berhasil! Silakan login.";
                        $nama = $email = '';
                    } else {
                        $error = "Registrasi gagal: " . $insert->error;
                    }
                } else {
                    $error = "Gagal menyiapkan query insert.";
                }
            }

            $check->close();
        } else {
            $error = "Gagal menyiapkan query pengecekan email.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi & Login - LOLUAS</title>
</head>
<body>
    <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
        <tr>
            <td align="center" valign="middle">
                <table border="0" cellpadding="10" cellspacing="0" width="420">
                    <tr>
                        <td align="center">
                            <h2>Daftar Akun Baru</h2>
                            <p>Isi form di bawah untuk membuat akun</p>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <?php if ($error): ?>
                                <p><strong>Kesalahan:</strong> <?= htmlspecialchars($error); ?></p>
                            <?php endif; ?>

                            <?php if ($success): ?>
                                <p><strong>Sukses:</strong> <?= htmlspecialchars($success); ?></p>
                            <?php endif; ?>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <form method="POST" action="">
                                <table border="0" cellpadding="6" cellspacing="0" width="100%">
                                    <tr>
                                        <td>
                                            <label for="nama">Nama Lengkap</label><br>
                                            <input type="text" id="nama" name="nama" value="<?= isset($nama) ? htmlspecialchars($nama) : ''; ?>" required>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <label for="email">Email</label><br>
                                            <input type="email" id="email" name="email" value="<?= isset($email) ? htmlspecialchars($email) : ''; ?>" required>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <label for="password">Password</label><br>
                                            <input type="password" id="password" name="password" required>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <label for="role">Status</label><br>
                                            <select name="role" id="role" required>
                                                <option value="">-- Pilih Status --</option>
                                                <option value="mahasiswa">Mahasiswa</option>
                                                <option value="dosen">Dosen</option>
                                            </select>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td align="center">
                                            <input type="submit" value="Daftar Sekarang">
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </td>
                    </tr>

                    <tr>
                        <td align="center">
                            <hr>
                            <p>Sudah punya akun? <a href="index.php">Login di sini</a></p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>