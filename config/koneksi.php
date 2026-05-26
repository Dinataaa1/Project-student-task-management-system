<?php

define('BASE_URL', '/LOLUAS/');

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