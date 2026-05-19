<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "data_login"
);

if (!$conn) {
    die("Koneksi gagal");
}
?>