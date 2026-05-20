<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'config/koneksi.php';

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'dosen') {
        header("Location: dashboard/dosen/dashboard.php");
    } else {
        header("Location: dashboard/mahasiswa/dashboard.php");
    }
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    $user   = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama']    = $user['username'];
        $_SESSION['role']    = $user['role'];

        if ($user['role'] == 'dosen') {
            header("Location: dashboard/dosen/dashboard.php");
        } else {
            header("Location: dashboard/mahasiswa/dashboard.php");
        }
        exit;
    } else {
        $error = "Email atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Akun</title>
</head>
<body>
    <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
        <tr>
            <td align="center" valign="middle">
                <table border="0" cellpadding="12" cellspacing="0" width="360">
                    <tr>
                        <td align="center">
                            <h2>Login</h2>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <?php if ($error) : ?>
                                <p><strong>Kesalahan:</strong> <?= htmlspecialchars($error); ?></p>
                            <?php endif; ?>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <form method="POST" action="">
                                <table border="0" cellpadding="6" cellspacing="0" width="100%">
                                    <tr>
                                        <td>
                                            <label for="email">Email</label><br>
                                            <input type="email" id="email" name="email" required>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <label for="password">Password</label><br>
                                            <input type="password" id="password" name="password" required>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td align="center">
                                            <input type="submit" value="Login">
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </td>
                    </tr>

                    <tr>
                        <td align="center">
                            <hr>
                            <p>Belum punya akun? <a href="login.php">Daftar di sini</a></p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>