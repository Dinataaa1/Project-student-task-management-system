<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "lol_db"
);

if (!$conn) {
    die("Koneksi gagal");
}
?>